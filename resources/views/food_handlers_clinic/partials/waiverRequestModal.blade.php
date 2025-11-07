 <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h1 class="modal-title fs-5" id="modalTitle">Modal title</h1>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <div class="col">
                     <label for="" class="form-label">Amount Being Waived</label>
                     <div class="input-group mb-3">

                         <span class="input-group-text">$</span>
                         <input type="text"
                             class="form-control @error('waiver_amount')
                                            is-invalid
                                        @enderror"
                             aria-label="Amount (to the nearest dollar)" name="waiver_amount"
                             value="{{ old('waiver_amount') }}" id="waiverAmountInput">
                         <span class="input-group-text">.00</span>
                         @error('waiver_amount')
                             <div class="is-invalid">{{ $message }}</div>
                         @enderror
                     </div>
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                 <button type="button" class="btn btn-primary" onclick="submitWaiver()">Make Request for Waiver</button>
             </div>
         </div>
     </div>
 </div>
