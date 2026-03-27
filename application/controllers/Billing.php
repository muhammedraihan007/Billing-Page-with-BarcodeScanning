<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Spare_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }

    /**
     * Main billing page
     */
    public function index() {
        $cart = $this->session->userdata('cart');
        if (!$cart) $cart = [];
        $data['cart'] = $cart;
        $data['cart_total'] = $this->_cart_total($cart);
        $this->load->view('billing/index', $data);
    }

    /**
     * AJAX: Search by barcode/partNo
     * Returns: batches if multiple, or direct add if single batch available
     */
    public function search_barcode() {
        $barcode = trim($this->input->post('barcode'));
        if (empty($barcode)) {
            echo json_encode(['status' => 'error', 'message' => 'Barcode is empty']);
            return;
        }

        $batches = $this->Spare_model->search_by_barcode($barcode);

        if (empty($batches)) {
            echo json_encode(['status' => 'no_stock', 'message' => 'No stock available for this item.']);
            return;
        }

        if (count($batches) === 1) {
            // Only one batch — send it back to auto-add
            echo json_encode([
                'status'  => 'single',
                'batch'   => $batches[0]
            ]);
        } else {
            // Multiple batches — show popup
            echo json_encode([
                'status'  => 'multiple',
                'batches' => $batches
            ]);
        }
    }

    /**
     * AJAX: Get batches for a partNo (used in popup)
     */
    public function get_batches() {
        $partno = trim($this->input->post('partno'));
        $batches = $this->Spare_model->get_batches_by_partno($partno);
        echo json_encode($batches);
    }

    /**
     * AJAX: Add item to cart (session-based)
     */
    public function add_to_cart() {
        $id           = (int)$this->input->post('id');
        $qty          = (float)$this->input->post('qty', TRUE) ?: 1;
        $product      = $this->Spare_model->get_by_id($id);

        if (!$product) {
            echo json_encode(['status' => 'error', 'message' => 'Product not found']);
            return;
        }

        if ($product['availableStock'] <= 0) {
            echo json_encode(['status' => 'no_stock', 'message' => 'Out of stock']);
            return;
        }

        $cart = $this->session->userdata('cart') ?: [];

        // Cart key: Id (each batch is separate line)
        $key = 'item_' . $id;

        if (isset($cart[$key])) {
            $new_qty = $cart[$key]['qty'] + $qty;
            if ($new_qty > $product['availableStock']) {
                echo json_encode(['status' => 'error', 'message' => 'Quantity exceeds available stock (' . $product['availableStock'] . ')']);
                return;
            }
            $cart[$key]['qty'] = $new_qty;
        } else {
            $cart[$key] = [
                'id'             => $product['Id'],
                'partNo'         => $product['partNo'],
                'partName'       => $product['partName'],
                'batchNo'        => $product['batchNo'],
                'sellingRate'    => $product['sellingRate'],
                'rateOfTax'      => $product['rateOfTax'],
                'discount'       => $product['discount'],
                'labourCost'     => $product['labourCost'],
                'unitOfMeasure'  => $product['unitOfMeasure'],
                'availableStock' => $product['availableStock'],
                'qty'            => $qty,
            ];
        }

        $this->session->set_userdata('cart', $cart);

        echo json_encode([
            'status'  => 'success',
            'message' => 'Added to cart',
            'cart'    => array_values($cart),
            'total'   => $this->_cart_total($cart)
        ]);
    }

    /**
     * AJAX: Remove item from cart
     */
    public function remove_from_cart() {
        $id   = (int)$this->input->post('id');
        $key  = 'item_' . $id;
        $cart = $this->session->userdata('cart') ?: [];
        unset($cart[$key]);
        $this->session->set_userdata('cart', $cart);

        echo json_encode([
            'status' => 'success',
            'cart'   => array_values($cart),
            'total'  => $this->_cart_total($cart)
        ]);
    }

    /**
     * AJAX: Update qty in cart
     */
    public function update_cart() {
        $id  = (int)$this->input->post('id');
        $qty = (float)$this->input->post('qty');
        $key = 'item_' . $id;
        $cart = $this->session->userdata('cart') ?: [];

        if (!isset($cart[$key])) {
            echo json_encode(['status' => 'error', 'message' => 'Item not in cart']);
            return;
        }

        if ($qty <= 0) {
            unset($cart[$key]);
        } else {
            if ($qty > $cart[$key]['availableStock']) {
                echo json_encode(['status' => 'error', 'message' => 'Exceeds available stock']);
                return;
            }
            $cart[$key]['qty'] = $qty;
        }

        $this->session->set_userdata('cart', $cart);
        echo json_encode([
            'status' => 'success',
            'cart'   => array_values($cart),
            'total'  => $this->_cart_total($cart)
        ]);
    }

    /**
     * AJAX: Clear cart
     */
    public function clear_cart() {
        $this->session->unset_userdata('cart');
        echo json_encode(['status' => 'success', 'message' => 'Cart cleared']);
    }

    /**
     * AJAX: Confirm & Checkout
     * Saves cart to table_spare_billing
     * Decreases stock in table_spare_register
     * Clears cart on success
     */
    public function checkout() {
        $cart = $this->session->userdata('cart') ?: [];

        if (empty($cart)) {
            echo json_encode(['status' => 'error', 'message' => 'Cart is empty']);
            return;
        }

        $customer = [
            'name'    => trim($this->input->post('customer_name'))   ?: 'Walk-in',
            'mobile'  => trim($this->input->post('customer_mobile'))  ?: '',
            'address' => trim($this->input->post('customer_address')) ?: '',
        ];

        $totals = $this->_cart_total($cart);
        $userid = 'admin'; // replace with session user when login is added

        $result = $this->Spare_model->save_bill($cart, $customer, $totals, $userid);

        if ($result['status'] === 'success') {
            $this->session->unset_userdata('cart');
            echo json_encode([
                'status'  => 'success',
                'bill_no' => $result['bill_no'],
                'message' => 'Bill saved! Bill No: ' . $result['bill_no'],
            ]);
        } else {
            echo json_encode([
                'status'  => 'error',
                'message' => $result['message'],
            ]);
        }
    }

    /**
     * AJAX: Checkout from manual row-based table (new billing UI)
     */
    public function checkout_rows() {
        $rows_json = $this->input->post('rows');
        $rows      = json_decode($rows_json, TRUE);

        if (empty($rows)) {
            echo json_encode(['status' => 'error', 'message' => 'No items to bill']);
            return;
        }

        $customer = [
            'name'    => trim($this->input->post('customer_name'))   ?: 'Walk-in',
            'mobile'  => trim($this->input->post('customer_mobile'))  ?: '',
            'address' => trim($this->input->post('customer_address')) ?: '',
        ];

        // Build cart-like array from rows
        $cart = [];
        foreach ($rows as $row) {
            if (empty($row['partNo']) || $row['rate'] <= 0) continue;
            $cart[] = [
                'id'            => isset($row['id']) ? (int)$row['id'] : 0,
                'partNo'        => $row['partNo'],
                'partName'      => $row['partName'],
                'batchNo'       => isset($row['batchNo']) ? $row['batchNo'] : '',
                'sellingRate'   => $row['rate'],
                'rateOfTax'     => $row['taxRate'],
                'discount'      => $row['discount'],
                'labourCost'    => 0,
                'unitOfMeasure' => isset($row['unitOfMeasure']) ? $row['unitOfMeasure'] : 'Nos',
                'qty'           => $row['qty'],
            ];
        }

        $totals = $this->_cart_total($cart);
        $result = $this->Spare_model->save_bill($cart, $customer, $totals, 'admin');

        if ($result['status'] === 'success') {
            $this->session->unset_userdata('cart');
            echo json_encode([
                'status'  => 'success',
                'bill_no' => $result['bill_no'],
                'message' => 'Bill saved! No: ' . $result['bill_no'],
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => $result['message']]);
        }
    }
    public function get_cart() {
        $cart = $this->session->userdata('cart') ?: [];
        echo json_encode([
            'cart'  => array_values($cart),
            'total' => $this->_cart_total($cart)
        ]);
    }

    // ----------------------------------------------------------
    // Private helpers
    // ----------------------------------------------------------

    private function _cart_total($cart) {
        $subtotal = 0;
        $tax      = 0;
        foreach ($cart as $item) {
            $line      = $item['sellingRate'] * $item['qty'];
            $disc_val  = is_numeric($item['discount']) ? (float)$item['discount'] : 0;
            $line_disc = $line - ($line * $disc_val / 100);
            $line_tax  = $line_disc * ($item['rateOfTax'] / 100);
            $subtotal += $line_disc;
            $tax      += $line_tax;
        }
        return [
            'subtotal' => round($subtotal, 2),
            'tax'      => round($tax, 2),
            'grand'    => round($subtotal + $tax, 2),
        ];
    }
}
