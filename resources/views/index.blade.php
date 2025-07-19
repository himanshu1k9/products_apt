<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Products | View</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="btns">
            <a href="javascript:void(0)" id="addBtn" class="btn btn-sm btn-primary my-1"><i class="fa fa-plus"></i>Add New</a>
        </div>
        <div class="row my-4 mt-4">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="tale" id="productsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Product Shord Desc</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @if(count($products) > 0)
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{ $counter++ }}</td>
                                        <td>{{ $product->title }}</td>
                                        <td> {!! substr($product->description, 0, 50) !!}... </td>
                                        <td>
                                            <a href="javascript:void(0)" data-id="{{$product->id}}" class="btn btn-sm btn-primary editBtn">Edit</a>
                                            <a href="javascript:void(0)" data-id="{{$product->id}}" class="btn btn-sm btn-danger delBtn">delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


<div class="modal fade" id="addEditProductModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add New Product</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addNewProduct">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" class="form-control" id="title" placeholder="enter title...">
            </div>
            <div class="mb-3">
                <label for="sourcr_id" class="form-label">Source Id</label>
                <input type="number" name="source_id" class="form-control" id="sourcr_id" placeholder="source id...">
            </div>
             <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" cols="50" rows="4"></textarea>
            </div>
             <div class="mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input type="text" name="slug" id="slug"  placeholder="enter slug">
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <input type="text" name="category" id="category"  placeholder="enter cat...">
            </div>
            <div class="mb-3">
                <label for="img_url" class="form-label">Image URL</label>
                <input type="text" name="img_url" id="img_url"  placeholder="enter cat...">
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
    </div>
  </div>
</div>


   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            let id;
            let isEditMode = false;
$('#productsTable').DataTable();

            $('#addBtn').on('click', function() {
                $('#addEditProductModal').modal('show');
                emptyModal();
            })

            $('#addNewProduct').on('submit', function(e) {
                e.preventDefault();
                let title = $('#title').val();
                let sourceId = $('#sourcr_id').val();
                let description = $('#description').val();
                let slug = $('#slug').val();
                let category = $('#category').val();
                let imgUrl = $('#img_url').val();

                let url = isEditMode && id > 0 ? "{{ url('/update') }}/" + id : "{{ url('/addproduct') }}";

                $.ajax({
                    method: 'POST',
                    url: url,
                    data: {
                        title: title,
                        sourceId:sourceId,
                        description:description,
                        slug:slug,
                        category:category,
                        imgUrl:imgUrl,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        console.log(res)
                        emptyModal();
                        $('#addEditProductModal').modal('hide');
                        isEditMode = false;
                        window.location.href='/';
                    },
                    error: function(err) {
                        console.log(err)
                    }
                })
            })

            $('.editBtn').on('click', function() {
                id = $(this).data('id');
                if(id > 0) {
                    $.ajax({
                        method: 'GET',
                        url: "{{ url('/getsingle') }}/" + id,
                        success: function(res) {
                            isEditMode = true;
                            const product = res.data;
                            $('#title').val(product.title);
                            $('#sourcr_id').val(product.source_id);
                            $('#description').val(product.description);
                            $('#slug').val(product.slug);
                            $('#category').val(product.category);
                            $('#img_url').val(product.image);
                            $('#staticBackdropLabel').html('Edit Product');
                            $('#addEditProductModal').modal('show');
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    })
                }
            })

            $('.delBtn').on('click', function() {
                let id = $(this).data('id');

                if(id > 0) {
                    let result = confirm('Are you sure to delete this product.');
                    if(result) {
                        $.ajax({
                            method:"POST",
                            url: "{{url('/destroy')}}/" + id,
                            data: {
                                _token: "{{csrf_token()}}"
                            },
                            success: function(res) {
                                window.location.href='/';
                                alert(res)
                            },
                            error: function(err) {
                                console.log(err)
                            }
                        })
                    }
                }
            })
        })

        function emptyModal() {
            $('#title').val('');
            $('#sourcr_id').val('');
            $('#description').val('');
            $('#slug').val('');
            $('#category').val('');
            $('#img_url').val('');
        }
    </script>
</body>
</html>
