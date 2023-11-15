<div id="editRecordModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editRecordModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg relative p-4">

        <!--Modal Content-->
        <div class="modal-content relative p-4 rounded-lg" style="background-color: #E1F1FA">
            <div class="modal-header flex justify-between items-center pb-3">
                <h2 class="font-semibold" style="font-size:20px">Edit Record</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="editRecord" method="POST">
                    @csrf
                    @method('PUT')
                    <div>
                        <div class="flex items-center">
                            <label for="accountName" class="w-32 text-left pr-2 mt-4">Account</label>
                            <select name="account_id" id="account_id" class="rounded-md border-0"
                                style="height: 30px; width:225px; padding:0px 10px; margin:15px 0px 0px 20px;" required>
                                <option value="" selected disabled>Select an account</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center">
                            <label for="recordType" class="w-32 text-left pr-2 mt-4">Type of Record</label>
                            <select name="type" id="type" class="rounded-md border-0"
                                style="height: 30px; width:225px; padding:0px 10px; margin:15px 0px 0px 20px;" required>
                                <option value="Expense">Expense</option>
                                <option value="Income">Income</option>
                            </select>
                        </div>
                        <div class="flex items-center">
                            <label for="category" class="w-32 text-left pr-2 mt-4">Category</label>
                            <select name="category_id" id="category_id" class="rounded-md border-0"
                                style="height: 30px; width:225px; padding:0px 10px; margin:15px 0px 0px 20px;" required>
                                <option value="" selected disabled>Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center">
                            <label for="amount" class="w-32 text-left pr-2 mt-4">Amount</label>
                            <input type="number" step="0.01" class="rounded-md border-0" name="amount"
                                id="amount"
                                style="height: 30px; width:225px; padding:0px 10px; margin:15px 0px 0px 20px; text-align:right;"
                                required>
                        </div>
                        <div class="flex items-center">
                            <label for="datetime" class="w-32 text-left pr-2 mt-4">Date</label>
                            <input type="datetime-local" class="rounded-md border-0" name="datetime" id="datetime"
                                style="height: 30px; width:225px; padding:0px 10px; margin:15px 0px 0px 20px;" required>
                        </div>
                        <div class="flex items-center">
                            <label for="description" class="w-32 text-left pr-2 mt-4">Description</label>
                            <textarea type="text" name="description" id="description" class="rounded-md flex-grow border-0" maxlength="255"
                                style="height: 60px; padding:0px 10px; margin:15px 0px 0px 20px;"></textarea>
                        </div>
                    </div>
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="user_id" name="user_id">
                    <div class="flex mt-6 justify-center text-black">
                        <button type="submit" class="mr-5"
                            style="background: #4D96EB; width:100px; height:26px; border:0px solid; border-radius: 5px">Save</button>
                        <button
                            style="background: #e5e5e5;width:100px; height:26px; border:0px solid; border-radius: 5px"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('#editRecord').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var recordId = $('#id').val(); 

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'PUT',
            url: '/record/update/' + recordId,
            data: formData,
            success: function(response) {
                
                window.location.reload();
                $('#editRecordModal').modal('hide');
            },
            error: function(error) {
                console.error('Error:', error);
                alert('An error occurred while updating the record.');
            }
        });
    });
</script>
