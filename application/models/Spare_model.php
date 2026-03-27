<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Spare_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Search product by barcode (partNo) - returns all batches with stock > 0
     */
    public function search_by_barcode($barcode) {
        $this->db->where('partNo', $barcode);
        $this->db->where('availableStock >', 0);
        $this->db->where('status', 1);
        $query = $this->db->get('table_spare_register');
        return $query->result_array();
    }

    /**
     * Get all batches for a partNo (for popup selection)
     */
    public function get_batches_by_partno($partno) {
        $this->db->select('Id, partNo, partName, batchNo, sellingRate, purchaseRate, maximumRP, availableStock, unitOfMeasure, rateOfTax, HSN, productGroup, discount, labourCost');
        $this->db->where('partNo', $partno);
        $this->db->where('availableStock >', 0);
        $this->db->where('status', 1);
        $this->db->order_by('batchNo', 'ASC');
        $query = $this->db->get('table_spare_register');
        return $query->result_array();
    }

    /**
     * Get single product by Id
     */
    public function get_by_id($id) {
        $query = $this->db->get_where('table_spare_register', ['Id' => $id]);
        return $query->row_array();
    }

    // ---------------------------------------------------------------
    // BILLING / CHECKOUT
    // ---------------------------------------------------------------

    /**
     * Generate next bill number — e.g. S_049 → S_050
     */
    public function get_next_bill_no() {
        $this->db->select_max('id');
        $row = $this->db->get('table_spare_billing')->row_array();
        $last_id = isset($row['id']) ? (int)$row['id'] : 0;
        $next    = $last_id + 1;
        return 'S_' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Save entire cart as billing rows + decrease stock
     * Wrapped in a transaction — rolls back fully on any failure
     *
     * @param array  $cart       Cart items from session
     * @param array  $customer   ['name', 'mobile', 'address']
     * @param array  $totals     ['subtotal', 'tax', 'grand']
     * @param string $userid     Logged-in user
     * @return array ['status' => 'success'|'error', 'bill_no' => ..., 'message' => ...]
     */
    public function save_bill($cart, $customer, $totals, $userid = 'admin') {
        $bill_no    = $this->get_next_bill_no();
        $date_today = date('Y-m-d');

        $this->db->trans_begin();

        try {
            foreach ($cart as $item) {
                $rate     = (float)$item['sellingRate'];
                $qty      = (float)$item['qty'];
                $disc_pct = is_numeric($item['discount']) ? (float)$item['discount'] : 0;
                $tax_pct  = (float)$item['rateOfTax'];

                $amount_before_tax = $rate * $qty * (1 - $disc_pct / 100);
                $half_tax          = $tax_pct / 2;                          // SGST = CGST
                $sgst_amt          = round($amount_before_tax * ($half_tax / 100), 4);
                $cgst_amt          = $sgst_amt;
                $tax_amount        = round($amount_before_tax * ($tax_pct / 100), 2);
                $total_amount      = round($amount_before_tax + $tax_amount, 2);
                $discount_amount   = round($rate * $qty * ($disc_pct / 100), 2);

                // --- Insert into table_spare_billing ---
                $billing_row = [
                    'serviceId'      => 0,
                    'customerID'     => $customer['name'],
                    'cstadd'         => $customer['address'],
                    'cstmob'         => $customer['mobile'],
                    'ProductId'      => $item['id'],
                    'batchNo'        => $item['batchNo'],
                    'billNo'         => $bill_no,
                    'dateOfBill'     => $date_today,
                    'partNo'         => $item['partNo'],
                    'partName'       => $item['partName'],
                    'maximumRP'      => $rate,
                    'Quantity'       => $qty,
                    'saleRate'       => $rate,
                    'taxAmount'      => $tax_amount,
                    'Discount'       => $disc_pct,
                    'TotalAmount'    => $total_amount,
                    'userid'         => $userid,
                    'taxpercent'     => $tax_pct,
                    'SGST'           => $half_tax,
                    'CGST'           => $half_tax,
                    'SGSTAmount'     => $sgst_amt,
                    'CGSTAmount'     => $cgst_amt,
                    'amountwtax'     => $amount_before_tax,
                    'IGST'           => 0,
                    'stateId'        => 1,
                    'freight'        => 0,
                    'insurance'      => 0,
                    'packing'        => 0,
                    'curuserid'      => $userid,
                    'HSN'            => isset($item['HSN']) ? $item['HSN'] : '',
                    'taxId'          => ($tax_pct > 0) ? 2 : 5,
                    'GSTIN'          => '',
                    'unitOfMeasure'  => $item['unitOfMeasure'],
                    'balanceToBPaid' => 0,
                    'type'           => 'B2C',
                    'prefix'         => 'CS',
                    'cessId'         => 0,
                    'cessPercent'    => 0,
                    'cessAmount'     => 0,
                    'paymenttyp'     => '1',
                    'discount_amount'=> $discount_amount,
                    'finyearid'      => 2,
                ];

                $this->db->insert('table_spare_billing', $billing_row);

                if ($this->db->affected_rows() < 1) {
                    throw new Exception('Failed to insert billing row for: ' . $item['partName']);
                }

                // --- Decrease stock in table_spare_register ---
                $this->db->set('availableStock', 'availableStock - ' . $qty, FALSE);
                $this->db->where('Id', $item['id']);
                $this->db->update('table_spare_register');

                if ($this->db->affected_rows() < 1) {
                    throw new Exception('Failed to update stock for: ' . $item['partName']);
                }
            }

            // All good — commit
            $this->db->trans_commit();
            return ['status' => 'success', 'bill_no' => $bill_no];

        } catch (Exception $e) {
            $this->db->trans_rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
