<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <title>Spare Parts Billing | Dhrupad Motors</title>
  <!-- Dashtreme CSS — exact same order as original -->
  <link href="http://localhost/Raihan/billing/assets/css/pace.min.css" rel="stylesheet"/>
  <script src="http://localhost/Raihan/billing/assets/js/pace.min.js"></script>
  <link href="http://localhost/Raihan/billing/assets/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="http://localhost/Raihan/billing/assets/css/animate.css" rel="stylesheet"/>
  <link href="http://localhost/Raihan/billing/assets/css/icons.css" rel="stylesheet"/>
  <link href="http://localhost/Raihan/billing/assets/css/sidebar-menu.css" rel="stylesheet"/>
  <link href="http://localhost/Raihan/billing/assets/css/app-style.css" rel="stylesheet"/>
  <style>
    /* Billing-specific styles only */
    .bill-tbl{width:100%;border-collapse:collapse;font-size:12.5px;}
    .bill-tbl thead th{background:rgba(255,255,255,0.05);padding:8px 8px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#aaa;border-bottom:1px solid rgba(255,255,255,0.1);white-space:nowrap;text-align:center;}
    .bill-tbl tbody td{padding:5px 5px;border-bottom:1px solid rgba(255,255,255,0.05);vertical-align:middle;text-align:center;}
    .bill-tbl tbody tr:hover{background:rgba(255,255,255,0.03);}
    .cell-input{width:100%;border:1px solid transparent;border-radius:3px;padding:4px 6px;font-size:12px;text-align:center;background:transparent;outline:none;color:#ccc;transition:all .15s;}
    .cell-input:hover{border-color:rgba(255,255,255,0.15);background:rgba(255,255,255,0.05);}
    .cell-input:focus{border-color:#4fc3f7;background:rgba(79,195,247,0.08);color:#fff;}
    .cell-input.readonly{color:#888;cursor:default;}
    .cell-input.readonly:hover,.cell-input.readonly:focus{border-color:transparent;background:transparent;box-shadow:none;}
    .cell-input.total-cell{color:#00c851;font-weight:700;}
    .btn-add-row{background:none;border:2px solid #00c851;color:#00c851;border-radius:50%;width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;font-size:11px;padding:0;transition:all .15s;}
    .btn-add-row:hover{background:#00c851;color:#fff;}
    .btn-rm-row{background:none;border:2px solid #ff4444;color:#ff4444;border-radius:50%;width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;font-size:11px;padding:0;transition:all .15s;}
    .btn-rm-row:hover{background:#ff4444;color:#fff;}
    .action-btns{display:flex;gap:4px;justify-content:center;align-items:center;}
    .bill-empty{text-align:center;padding:36px;color:#666;}
    .bill-empty i{font-size:32px;display:block;margin-bottom:8px;}
    .totals-row{display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid rgba(255,255,255,0.06);font-size:13px;}
    .totals-row:last-child{border:none;}
    .totals-row .lbl{color:#aaa;}
    .totals-row .val{font-weight:600;font-family:monospace;color:#e0e0e0;}
    .grand-val{font-size:16px;font-weight:700;color:#4fc3f7 !important;font-family:monospace;}
    .b-input{width:110px;border:1px solid rgba(255,255,255,0.15);border-radius:3px;padding:3px 8px;font-size:13px;text-align:right;background:rgba(255,255,255,0.05);color:#e0e0e0;outline:none;}
    .b-input:focus{border-color:#4fc3f7;}
    .batch-badge{background:rgba(255,193,7,0.2);color:#ffc107;border-radius:3px;padding:1px 6px;font-size:10px;font-weight:700;}
    .batch-opt{border:1px solid rgba(255,255,255,0.1);border-radius:5px;padding:12px 14px;margin-bottom:8px;cursor:pointer;display:flex;justify-content:space-between;align-items:center;transition:all .15s;background:rgba(255,255,255,0.03);}
    .batch-opt:hover{border-color:#4fc3f7;background:rgba(79,195,247,0.08);}
    .batch-opt-price{font-size:18px;font-weight:700;color:#4fc3f7;font-family:monospace;}
    .toast-wrap{position:fixed;bottom:18px;right:18px;z-index:9999;}
    .toast-item{min-width:200px;padding:10px 14px;border-radius:4px;margin-top:7px;font-size:12px;font-weight:500;display:flex;align-items:center;gap:7px;box-shadow:0 3px 12px rgba(0,0,0,0.4);color:#fff;animation:tIn .2s ease;}
    @keyframes tIn{from{transform:translateY(6px);opacity:0;}to{transform:translateY(0);opacity:1;}}
    .toast-item.success{background:#00c851;}
    .toast-item.error{background:#ff4444;}
    .toast-item.info{background:#4fc3f7;color:#000;}
    #barcodeInput{position:fixed;opacity:0;pointer-events:none;width:1px;height:1px;top:0;left:0;}
    .card{background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);}
    .card .card-body{color:#ccc;}
    .form-control{background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.15);color:#e0e0e0;}
    .form-control:focus{background:rgba(255,255,255,0.1);border-color:#4fc3f7;color:#fff;box-shadow:none;}
    .form-control::placeholder{color:#666;}
    .form-control[readonly]{background:rgba(255,255,255,0.04);color:#888;}
    select.form-control option{background:#1e2a40;color:#e0e0e0;}
    label{color:#aaa;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.6px;}
    .scan-info{font-size:12px;color:#aaa;}
    .scan-info span{font-weight:600;}

    /* Dashtreme layout fix — uses content-wrapper margin NOT flexbox */
    #wrapper{ width:100%; }
    .content-wrapper{ margin-left:250px; transition:all 0.3s; }
    #wrapper.toggled .content-wrapper{ margin-left:0; }
    #wrapper.toggled #sidebar-wrapper{ width:0; overflow:hidden; }
    /* topbar must also shift */
    .topbar-nav .navbar{ margin-left:250px; transition:all 0.3s; }
    #wrapper.toggled .topbar-nav .navbar{ margin-left:0; }
  </style>
</head>

<body class="bg-theme bg-theme1">

<!-- hidden barcode input -->
<input type="text" id="barcodeInput" autocomplete="off">

<!-- Start wrapper -->
<div id="wrapper">

  <!-- Start sidebar -->
  <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
    <div class="brand-logo">
      <a href="#">
        <img src="http://localhost/Raihan/billing/assets/images/logo-icon.png" class="logo-icon" alt="logo">
        <h5 class="logo-text">Dhrupad Motors</h5>
      </a>
    </div>
    <ul class="sidebar-menu do-nicescrol">
      <li class="sidebar-header">SPARE MANAGEMENT</li>
      <li class="active">
        <a href="<?= base_url('index.php/billing') ?>">
          <i class="zmdi zmdi-money-box"></i> <span>Spare Billing</span>
        </a>
      </li>
      <li>
        <a href="#">
          <i class="zmdi zmdi-file-text"></i> <span>Billing Report</span>
        </a>
      </li>
      <li>
        <a href="#">
          <i class="zmdi zmdi-store"></i> <span>Purchase Register</span>
        </a>
      </li>
      <li>
        <a href="#">
          <i class="zmdi zmdi-swap"></i> <span>Spare Transfer</span>
        </a>
      </li>
      <li>
        <a href="#">
          <i class="zmdi zmdi-undo"></i> <span>Sales Return</span>
        </a>
      </li>
      <li class="sidebar-header">REPORTS</li>
      <li>
        <a href="#">
          <i class="zmdi zmdi-chart"></i> <span>Sales Report</span>
        </a>
      </li>
    </ul>
  </div>
  <!-- End sidebar -->

  <!-- Start topbar -->
  <header class="topbar-nav">
    <nav class="navbar navbar-expand fixed-top">
      <ul class="navbar-nav mr-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link toggle-menu" href="javascript:void();">
            <i class="icon-menu menu-icon"></i>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav align-items-center right-nav-link">
        <li class="nav-item">
          <span class="nav-link scan-info">
            <i class="fa fa-barcode mr-1" style="color:#4fc3f7;"></i>
            Scanner active &nbsp;<span id="scanStatus"></span>
          </span>
        </li>
        <li class="nav-item">
          <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown" href="#">
            <span class="user-profile">
              <img src="https://via.placeholder.com/110x110" class="img-circle" alt="user avatar">
            </span>
          </a>
        </li>
      </ul>
    </nav>
  </header>
  <!-- End topbar -->

  <!-- Start content-wrapper -->
  <div class="content-wrapper">
    <div class="page-content">

      <!-- Customer Details -->
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Customer Name *</label>
                    <input type="text" id="customerName" class="form-control" placeholder="Customer name">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Contact No.</label>
                    <input type="text" id="customerMobile" class="form-control" placeholder="Mobile">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>GSTIN</label>
                    <input type="text" id="customerGSTIN" class="form-control" placeholder="GSTIN">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Date of Bill</label>
                    <input type="text" class="form-control" value="<?= date('d-m-Y') ?>" readonly>
                  </div>
                </div>
                <div class="col-md-1">
                  <div class="form-group">
                    <label>Payment *</label>
                    <select class="form-control" id="paymentMode">
                      <option>CASH</option>
                      <option>CARD</option>
                      <option>UPI</option>
                      <option>CREDIT</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Bill No.</label>
                    <input type="text" id="billNoDisplay" class="form-control" value="Auto Generated" readonly style="color:#4fc3f7;font-weight:700;">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-5">
                  <div class="form-group">
                    <label>Address</label>
                    <input type="text" id="customerAddress" class="form-control" placeholder="Address (optional)">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bill Table -->
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="text-white mb-0"><i class="zmdi zmdi-format-list-bulleted mr-2" style="color:#4fc3f7;"></i>Bill Items</h6>
                <span class="badge badge-primary" id="cartCount">0 items</span>
              </div>
              <div class="table-responsive">
                <table class="bill-tbl" id="billTable">
                  <thead>
                    <tr>
                      <th style="width:36px;">SL#</th>
                      <th style="width:100px;">Part #</th>
                      <th style="min-width:130px;">Part Name</th>
                      <th style="width:70px;">Quantity</th>
                      <th style="width:65px;">Tax %</th>
                      <th style="width:65px;">Cess %</th>
                      <th style="width:80px;">Rate</th>
                      <th style="width:80px;">Discount</th>
                      <th style="width:95px;">Taxable Amt.</th>
                      <th style="width:75px;">Tax</th>
                      <th style="width:80px;">Cess Amt.</th>
                      <th style="width:85px;">Total</th>
                      <th style="width:56px;"></th>
                    </tr>
                  </thead>
                  <tbody id="billTableBody"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Totals -->
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <!-- Left -->
                <div class="col-md-4" style="border-right:1px solid rgba(255,255,255,0.08);">
                  <div class="totals-row"><span class="lbl">Discount:</span><span class="val" id="totalDiscount">0.00</span></div>
                  <div class="totals-row"><span class="lbl">Round Off:</span><span class="val">0.00</span></div>
                  <div class="totals-row">
                    <span class="lbl">Received Amt:</span>
                    <span class="val"><input type="number" id="receivedAmt" class="b-input" placeholder="0.00" oninput="calcBalance()"></span>
                  </div>
                </div>
                <!-- Mid -->
                <div class="col-md-4" style="border-right:1px solid rgba(255,255,255,0.08);">
                  <div class="totals-row"><span class="lbl">Grand Total:</span><span class="val grand-val" id="totalGrand">0.00</span></div>
                  <div class="totals-row">
                    <span class="lbl">Other Amounts:</span>
                    <span class="val"><input type="number" id="otherAmounts" class="b-input" placeholder="0.00" oninput="calcBalance()"></span>
                  </div>
                  <div class="totals-row">
                    <span class="lbl">Balance To Be Paid:</span>
                    <span class="val" id="balancePaid" style="color:#ff4444;">0.00</span>
                  </div>
                </div>
                <!-- Right -->
                <div class="col-md-4">
                  <div class="totals-row"><span class="lbl">Subtotal (before tax):</span><span class="val" id="totalSubtotal">0.00</span></div>
                  <div class="totals-row"><span class="lbl">Tax (GST):</span><span class="val" id="totalTax">0.00</span></div>
                </div>
              </div>
              <!-- Action buttons -->
              <div class="row mt-3">
                <div class="col-md-12 text-right">
                  <button class="btn btn-outline-secondary btn-sm mr-2" onclick="clearAll()">
                    <i class="fa fa-eraser mr-1"></i> CLEAR FIELDS
                  </button>
                  <button class="btn btn-outline-secondary btn-sm mr-2" onclick="window.print()">
                    <i class="fa fa-print mr-1"></i> PRINT
                  </button>
                  <button id="checkoutBtn" class="btn btn-primary btn-sm px-4" onclick="checkout()">
                    <i class="fa fa-save mr-1"></i> SAVE &amp; PRINT
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  <!-- End content-wrapper -->

</div>
<!-- End wrapper -->

<!-- Batch Modal -->
<div class="modal fade" id="batchModal" tabindex="0">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="background:#1e2a40;border:1px solid rgba(255,255,255,0.1);">
      <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,0.1);">
        <h6 class="modal-title text-white font-weight-bold mb-0">
          <i class="zmdi zmdi-layers text-warning mr-2"></i>
          Select Batch &mdash; <span id="batchPartName" style="color:#4fc3f7;"></span>
          <small style="color:#aaa;font-size:11px;font-weight:400;margin-left:10px;">↑↓ Arrow keys to navigate, Enter to select</small>
        </h6>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body" style="padding:16px;">
        <div id="batchCards"></div>
      </div>
    </div>
  </div>
</div>

<div class="toast-wrap" id="toastContainer"></div>

<!-- Dashtreme JS — exact same order as original -->
<script src="http://localhost/Raihan/billing/assets/js/jquery.min.js"></script>
<script src="http://localhost/Raihan/billing/assets/js/popper.min.js"></script>
<script src="http://localhost/Raihan/billing/assets/js/bootstrap.min.js"></script>
<script src="http://localhost/Raihan/billing/assets/js/sidebar-menu.js"></script>
<script src="http://localhost/Raihan/billing/assets/js/app-script.js"></script>

<script>
// Sidebar toggle
$(document).on('click', '.toggle-menu', function(e){
    e.preventDefault();
    $('#wrapper').toggleClass('toggled');
});

const BASE_URL = "<?= base_url('index.php/') ?>";
let rows = [];
let selectedBatchIdx = -1; // for keyboard navigation

$(document).ready(function(){
    addEmptyRow();
    focusBarcode();

    // Barcode input capture
    $('#barcodeInput').on('keydown', function(e){
        if(e.key === 'Enter'){
            e.preventDefault();
            const val = $(this).val().trim();
            if(val){ triggerScan(val); }
            $(this).val('');
        }
    });

    // Keyboard navigation inside batch modal
    $(document).on('keydown', function(e){
        if(!$('#batchModal').hasClass('show')) return;
        const cards = $('.batch-opt');
        if(cards.length === 0) return;

        if(e.key === 'ArrowDown'){
            e.preventDefault();
            selectedBatchIdx = Math.min(selectedBatchIdx + 1, cards.length - 1);
            highlightBatch(cards);
        } else if(e.key === 'ArrowUp'){
            e.preventDefault();
            selectedBatchIdx = Math.max(selectedBatchIdx - 1, 0);
            highlightBatch(cards);
        } else if(e.key === 'Enter'){
            e.preventDefault();
            if(selectedBatchIdx >= 0 && selectedBatchIdx < cards.length){
                const batchData = JSON.parse($(cards[selectedBatchIdx]).attr('data-batch'));
                $('#batchModal').modal('hide');
                addBatchToRows(batchData);
            }
        } else if(e.key === 'Escape'){
            $('#batchModal').modal('hide');
        }
    });

    // When modal opens — auto-highlight first item, allow keyboard immediately
    $('#batchModal').on('shown.bs.modal', function(){
        selectedBatchIdx = 0;
        highlightBatch($('.batch-opt'));
        // focus the modal itself so keyboard works without clicking
        $(this).focus();
    });

    // When modal closes — refocus barcode immediately
    $('#batchModal').on('hidden.bs.modal', function(){
        selectedBatchIdx = -1;
        setTimeout(focusBarcode, 50);
    });

    // Refocus barcode when clicking non-input areas
    $(document).on('click', function(e){
        if($('#batchModal').hasClass('show')) return;
        const tag = e.target.tagName.toLowerCase();
        if(!['input','select','textarea','button','a'].includes(tag)){
            focusBarcode();
        }
    });
});

function focusBarcode(){
    const el = document.getElementById('barcodeInput');
    if(el){ el.focus(); }
}

function highlightBatch(cards){
    cards.each(function(i){
        if(i === selectedBatchIdx){
            $(this).css({'border-color':'#4fc3f7','background':'rgba(79,195,247,0.15)'});
            this.scrollIntoView({block:'nearest'});
        } else {
            $(this).css({'border-color':'rgba(255,255,255,0.1)','background':'rgba(255,255,255,0.03)'});
        }
    });
}

// ================================================================
// ROW MANAGEMENT
// ================================================================
function emptyRowObj(){
    return { id:null, partNo:'', partName:'', batchNo:'', qty:1, taxRate:0, cessRate:0, rate:0, discount:0, taxable:0, taxAmt:0, cessAmt:0, total:0, availableStock:9999, unitOfMeasure:'Nos', fromDB:false };
}

function addEmptyRow(){
    rows.push(emptyRowObj());
    renderTable();
}

function removeRow(idx){
    if(rows.length === 1){
        rows[0] = emptyRowObj();
    } else {
        rows.splice(idx, 1);
    }
    renderTable();
    updateTotals();
}

// ================================================================
// SCAN — always shows popup for every item
// ================================================================
function triggerScan(barcode){
    setStatus('Searching...','info');

    $.post(BASE_URL+'billing/search_barcode',{barcode:barcode},function(res){
        if(res.status==='error'){
            setStatus(res.message,'error'); showToast(res.message,'error');
        } else if(res.status==='no_stock'){
            setStatus('No stock: '+barcode,'error'); showToast('No stock available.','error');
        } else if(res.status==='single'){
            // ALWAYS show popup — even for single batch
            openBatchModal(res.batches || [res.batch]);
            setStatus('','info');
        } else if(res.status==='multiple'){
            openBatchModal(res.batches);
            setStatus('','info');
        }
    },'json').fail(function(){ setStatus('Server error.','error'); });
}

// ================================================================
// ADD BATCH TO ROWS — handles qty increase or new row
// ================================================================
function addBatchToRows(batch){
    const batchId = batch.Id;
    const batchNo = batch.batchNo;
    const rate    = parseFloat(batch.sellingRate)||0;

    // Same batch + same price → increase qty on existing row
    const existingIdx = rows.findIndex(function(r){
        return r.id === batchId && r.batchNo === batchNo && r.rate === rate && r.fromDB;
    });

    if(existingIdx !== -1){
        rows[existingIdx].qty += 1;
        calcRow(existingIdx);
        renderTable();
        updateTotals();
        showToast('Qty updated: '+batch.partName+' × '+rows[existingIdx].qty,'success');
        setTimeout(focusBarcode, 100);
        return;
    }

    // New batch or different price → fill next empty row
    let targetIdx = rows.findIndex(function(r){ return r.partNo === '' && !r.fromDB; });
    if(targetIdx === -1){
        rows.push(emptyRowObj());
        targetIdx = rows.length - 1;
    }

    rows[targetIdx] = {
        id: batchId,
        partNo:   batch.partNo,
        partName: batch.partName,
        batchNo:  batchNo,
        qty: 1,
        taxRate:  parseFloat(batch.rateOfTax)||0,
        cessRate: 0,
        rate:     rate,
        discount: parseFloat(batch.discount)||0,
        availableStock: parseFloat(batch.availableStock)||0,
        unitOfMeasure:  batch.unitOfMeasure||'Nos',
        fromDB: true,
        taxable:0, taxAmt:0, cessAmt:0, total:0
    };
    calcRow(targetIdx);

    // Always ensure a blank row exists after the filled one
    const hasEmpty = rows.some(function(r){ return r.partNo === '' && !r.fromDB; });
    if(!hasEmpty){ rows.push(emptyRowObj()); }

    renderTable();
    updateTotals();
    showToast('Added: '+batch.partName,'success');
    setTimeout(focusBarcode, 100);
}

// ================================================================
// BATCH MODAL — always used for single AND multiple
// ================================================================
function openBatchModal(batches){
    document.getElementById('batchPartName').textContent = batches[0].partName;
    const c = document.getElementById('batchCards');
    c.innerHTML = '';
    selectedBatchIdx = -1;

    batches.forEach(function(b){
        const rate    = parseFloat(b.sellingRate)||0;
        const taxRate = parseFloat(b.rateOfTax)||0;
        const disc    = parseFloat(b.discount)||0;
        const div = document.createElement('div');
        div.className = 'batch-opt';
        div.setAttribute('data-batch', JSON.stringify(b));
        div.innerHTML =
            '<div>'+
                '<div class="mb-1">'+
                    '<span class="batch-badge">Batch: '+escHtml(b.batchNo)+'</span>'+
                    ' <code style="font-size:11px;color:#4fc3f7;">'+escHtml(b.partNo)+'</code>'+
                '</div>'+
                '<div style="font-size:12px;color:#aaa;">'+
                    'Stock: <strong style="color:#ccc;">'+b.availableStock+' '+escHtml(b.unitOfMeasure)+'</strong>'+
                    ' | GST: '+taxRate+'%'+(disc>0?' | Disc: '+disc+'%':'')+
                '</div>'+
            '</div>'+
            '<div class="text-right">'+
                '<div class="batch-opt-price">&#8377; '+rate.toFixed(2)+'</div>'+
                '<div style="font-size:11px;color:#aaa;margin-bottom:4px;">Avail: '+b.availableStock+' units</div>'+
                '<button class="btn btn-primary btn-sm" onclick="selectBatchFromModal(this)">'+
                    '<i class="fa fa-check mr-1"></i> Select [Enter]'+
                '</button>'+
            '</div>';
        div.addEventListener('click', function(e){
            if(e.target.tagName.toLowerCase() === 'button' || e.target.closest('button')) return;
            const batchData = JSON.parse(this.getAttribute('data-batch'));
            $('#batchModal').modal('hide');
            addBatchToRows(batchData);
        });
        c.appendChild(div);
    });

    // Show modal — Bootstrap will handle focus
    $('#batchModal').modal({backdrop:'static', keyboard:false});
    $('#batchModal').modal('show');
}

function selectBatchFromModal(btn){
    const batchData = JSON.parse(btn.closest('.batch-opt').getAttribute('data-batch'));
    $('#batchModal').modal('hide');
    addBatchToRows(batchData);
}

// ================================================================
// CELL EDITING
// ================================================================
function onCellChange(idx, field, value){
    if(field==='partNo'){ rows[idx].partNo=value; rows[idx].fromDB=false; }
    else if(field==='partName'){ rows[idx].partName=value; }
    else if(field==='qty'){ rows[idx].qty=parseFloat(value)||0; }
    else if(field==='taxRate'){ rows[idx].taxRate=parseFloat(value)||0; }
    else if(field==='cessRate'){ rows[idx].cessRate=parseFloat(value)||0; }
    else if(field==='rate'){ rows[idx].rate=parseFloat(value)||0; }
    else if(field==='discount'){ rows[idx].discount=parseFloat(value)||0; }
    calcRow(idx);
    updateTotals();
    updateCalcCells(idx);
}

function calcRow(idx){
    const r=rows[idx];
    const gross=r.rate*r.qty;
    const discAmt=gross*(r.discount/100);
    r.taxable=r2(gross-discAmt);
    r.taxAmt=r2(r.taxable*(r.taxRate/100));
    r.cessAmt=r2(r.taxable*(r.cessRate/100));
    r.total=r2(r.taxable+r.taxAmt+r.cessAmt);
}

function updateCalcCells(idx){
    const r=rows[idx];
    const row=document.querySelector('tr[data-idx="'+idx+'"]');
    if(!row)return;
    row.querySelector('.c-taxable').value=r.taxable.toFixed(2);
    row.querySelector('.c-tax').value=r.taxAmt.toFixed(2);
    row.querySelector('.c-cess').value=r.cessAmt.toFixed(2);
    row.querySelector('.c-total').value=r.total.toFixed(2);
}

function tryLookup(idx, partNo){
    if(!partNo) return;
    $.post(BASE_URL+'billing/search_barcode',{barcode:partNo},function(res){
        if(res.status==='single'){ openBatchModal([res.batch]); }
        else if(res.status==='multiple'){ openBatchModal(res.batches); }
    },'json');
}

// ================================================================
// RENDER TABLE
// ================================================================
function renderTable(){
    const tbody=document.getElementById('billTableBody');
    document.getElementById('cartCount').textContent=rows.filter(function(r){return r.partNo!=='';}).length+' items';
    if(rows.length===0){ addEmptyRow(); return; }
    let html='';
    rows.forEach(function(r,idx){
        html+=
        '<tr data-idx="'+idx+'">'+
            '<td style="color:#666;font-size:12px;">'+(idx+1)+'</td>'+
            '<td><input class="cell-input" type="text" value="'+escAttr(r.partNo)+'" placeholder="Part #"'+
                ' onchange="onCellChange('+idx+',\'partNo\',this.value)"'+
                ' onblur="if(this.value&&!rows['+idx+'].fromDB){tryLookup('+idx+',this.value);}"></td>'+
            '<td><input class="cell-input" type="text" value="'+escAttr(r.partName)+'" placeholder="Part Name"'+
                ' onchange="onCellChange('+idx+',\'partName\',this.value)"></td>'+
            '<td><input class="cell-input" type="number" value="'+r.qty+'" min="0.01" step="1"'+
                ' onchange="onCellChange('+idx+',\'qty\',this.value)"></td>'+
            '<td><input class="cell-input" type="number" value="'+r.taxRate+'" min="0" step="0.5"'+
                ' onchange="onCellChange('+idx+',\'taxRate\',this.value)"></td>'+
            '<td><input class="cell-input" type="number" value="'+r.cessRate+'" min="0" step="0.5"'+
                ' onchange="onCellChange('+idx+',\'cessRate\',this.value)"></td>'+
            '<td><input class="cell-input" type="number" value="'+r.rate+'" min="0" step="0.01"'+
                ' onchange="onCellChange('+idx+',\'rate\',this.value)"></td>'+
            '<td><input class="cell-input" type="number" value="'+r.discount+'" min="0" max="100" step="0.5"'+
                ' onchange="onCellChange('+idx+',\'discount\',this.value)"></td>'+
            '<td><input class="cell-input readonly c-taxable" type="number" value="'+r.taxable.toFixed(2)+'" readonly tabindex="-1"></td>'+
            '<td><input class="cell-input readonly c-tax" type="number" value="'+r.taxAmt.toFixed(2)+'" readonly tabindex="-1"></td>'+
            '<td><input class="cell-input readonly c-cess" type="number" value="'+r.cessAmt.toFixed(2)+'" readonly tabindex="-1"></td>'+
            '<td><input class="cell-input readonly total-cell c-total" type="number" value="'+r.total.toFixed(2)+'" readonly tabindex="-1"></td>'+
            '<td><div class="action-btns">'+
                '<button class="btn-add-row" onclick="addEmptyRow()" title="Add row"><i class="fa fa-plus"></i></button>'+
                '<button class="btn-rm-row" onclick="removeRow('+idx+')" title="Remove"><i class="fa fa-times"></i></button>'+
            '</div></td>'+
        '</tr>';
    });
    tbody.innerHTML=html;
}

// ================================================================
// TOTALS
// ================================================================
function updateTotals(){
    let sub=0,tax=0,disc=0;
    rows.forEach(function(r){
        disc+=r.rate*r.qty*(r.discount/100);
        sub+=r.taxable; tax+=r.taxAmt+r.cessAmt;
    });
    document.getElementById('totalDiscount').textContent=r2(disc).toFixed(2);
    document.getElementById('totalSubtotal').textContent=r2(sub).toFixed(2);
    document.getElementById('totalTax').textContent=r2(tax).toFixed(2);
    document.getElementById('totalGrand').textContent=r2(sub+tax).toFixed(2);
    calcBalance();
}

function calcBalance(){
    const grand=parseFloat(document.getElementById('totalGrand').textContent)||0;
    const other=parseFloat(document.getElementById('otherAmounts').value)||0;
    const received=parseFloat(document.getElementById('receivedAmt').value)||0;
    const bal=(grand+other)-received;
    document.getElementById('balancePaid').textContent=bal.toFixed(2);
    document.getElementById('balancePaid').style.color=bal>0?'#ff4444':'#00c851';
}

// ================================================================
// CHECKOUT
// ================================================================
function checkout(){
    const validRows=rows.filter(function(r){return r.partNo!==''&&r.rate>0;});
    if(validRows.length===0){showToast('No items to bill!','error');return;}
    const nm=document.getElementById('customerName').value.trim();
    if(!nm){showToast('Enter customer name','error');document.getElementById('customerName').focus();return;}
    const btn=document.getElementById('checkoutBtn');
    btn.disabled=true;
    btn.innerHTML='<span class="spinner-border spinner-border-sm"></span> Saving...';
    $.post(BASE_URL+'billing/checkout_rows',{
        rows:JSON.stringify(validRows),
        customer_name:nm,
        customer_mobile:document.getElementById('customerMobile').value.trim(),
        customer_address:document.getElementById('customerAddress').value.trim()
    },function(res){
        btn.disabled=false;
        btn.innerHTML='<i class="fa fa-save mr-1"></i> SAVE & PRINT';
        if(res.status==='success'){
            rows=[];addEmptyRow();
            document.getElementById('customerName').value='';
            document.getElementById('customerMobile').value='';
            document.getElementById('receivedAmt').value='';
            document.getElementById('billNoDisplay').value=res.bill_no;
            updateTotals();
            showToast('Bill saved! No: '+res.bill_no,'success');
            setTimeout(function(){alert('Bill Saved!\nBill No: '+res.bill_no+'\nStock updated.');},300);
        }else{showToast(res.message,'error');}
    },'json').fail(function(){
        btn.disabled=false;
        btn.innerHTML='<i class="fa fa-save mr-1"></i> SAVE & PRINT';
        showToast('Server error.','error');
    });
}

function clearAll(){
    if(!confirm('Clear all fields?'))return;
    rows=[];addEmptyRow();
    document.getElementById('customerName').value='';
    document.getElementById('customerMobile').value='';
    document.getElementById('customerAddress').value='';
    document.getElementById('receivedAmt').value='';
    document.getElementById('otherAmounts').value='';
    document.getElementById('billNoDisplay').value='Auto Generated';
    updateTotals();showToast('Cleared','info');
}

function setStatus(msg,type){
    const el=document.getElementById('scanStatus');
    el.textContent=msg;
    el.style.color=type==='success'?'#00c851':type==='error'?'#ff4444':'#4fc3f7';
}

function showToast(msg,type){
    const icons={success:'fa-check-circle',error:'fa-times-circle',info:'fa-info-circle'};
    const t=document.createElement('div');t.className='toast-item '+type;
    t.innerHTML='<i class="fa '+icons[type]+'"></i> '+escHtml(msg);
    document.getElementById('toastContainer').appendChild(t);
    setTimeout(function(){t.remove();},3000);
}

function escHtml(s){if(!s)return'';return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
function escAttr(s){if(!s)return'';return String(s).replace(/"/g,'&quot;').replace(/'/g,'&#39;');}
function r2(v){return Math.round(v*100)/100;}
</script>
</body>
</html>

    