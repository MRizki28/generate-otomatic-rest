@extends('be.Base')
@section('content')
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Project</h6>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#PhoneModal" id="#myBtn">
                    Tambah Data
                </button>
            </div>
            <div class="p-3">
                <div class="row" id="data-container">
                    <div class="table-responsive p-3">
                        <table id="dataTable" class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Uuid</th>
                                    <th>image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data from database will be shown here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal create --}}
    <div class="modal fade" id="PhoneModal" tabindex="-1" role="dialog" aria-labelledby="PhoneModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="PhoneModalLabel">Tambah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formTambah" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="uuid">
                        <input type="hidden" name="image_kartu_anggota">
                        <div class="form-group">
                            <label for="nama">nama</label>
                            <input type="text" class="form-control" name="nama" id="nama"
                                placeholder="Input Here">
                        </div>
                        <div class="form-group">
                            <label for="template_id">Template</label>
                            <select name="template_id" id="template_id" class="form-control">
                                <option value="">-- Pilih Template --</option>
                            </select>
                        </div>
                        <input type="hidden" name="image_kartu_anggota">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-primary">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
       

       $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// create
$(document).ready(function() {
    var formTambah = $('#formTambah');

    formTambah.on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: '{{ url('v2/anggota/create/') }}',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(data) {
                if (data.message === 'check your validation') {
                    var error = data.errors;
                    var errorMessage = "";
                    $.each(error, function(keu, value) {
                        errorMessage += value[0] + "<br>";
                    });
                    Swal.fire({
                        title: 'Error',
                        html: errorMessage,
                        icon: 'error',
                        timer: 5000,
                        showConfirmButton: true
                    });
                } else {
                    var pdfUrl = data.pdf_url;
                    window.location.href = pdfUrl;
                }
            },
            error: function(data) {
                var error = data.responseJSON.errors;
                var errorMessage = "";
                $.each(error, function(key, value) {
                    errorMessage += value[0] + "<br>";
                });
                Swal.fire({
                    title: 'Error',
                    html: errorMessage,
                    icon: 'error',
                    timer: 5000,
                    showConfirmButton: true
                });
            }
        });
    });
});

    // get detail data and populate the options
    $.ajax({
            url: "{{ url('v1/template') }}",
            method: "GET",
            dataType: "json",
            success: function(response) {
                var options = '';
                $.each(response.data, function(index, item) {
                    options += '<option value="' + item.id + '">' + item.id + '</option>';
                });
                $('#template_id').append(options);
              
            },
            error: function() {
                console.log("Failed to get data from server");
            }
        });
 
    </script>
@endsection
