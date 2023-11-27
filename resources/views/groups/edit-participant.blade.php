<div id="editParticipantModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editParticipantModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <!--Modal Content-->
        <div class="modal-content relative p-4 text-center rounded-lg sm:p-5" style="background-color: #E1F1FA">
            <div class="modal-header flex items-center justify-between">
                <h2 class="modal-title" style="font-weight: bold; font-size:20px">Edit Participant</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="modal-body">
                <!-- Group invite form -->
                <form action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="accountName">Enter Participant's Email</label>
                        <input type="email" name="email" class="rounded-md border-0"
                            style="height: 30px; padding:0px 10px; margin:15px 0px 0px 11px; width:50%"
                            placeholder="user@email.com" required>
                    </div>
                    <div class="flex mt-6 justify-center">
                        <button type="submit" style="background: #4D96EB; width:100px; height:26px; border:0px solid; border-radius: 5px">Save</button>
                        <button type="button" data-bs-dismiss="modal" style="background: #fff; width:100px; height:26px; border:0px solid; border-radius: 5px">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>