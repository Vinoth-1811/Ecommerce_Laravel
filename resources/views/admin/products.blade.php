@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>All Products</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">All Products</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search">
                        <fieldset class="name">
                            <input type="text" placeholder="Search here..." class="" name="name"
                                tabindex="2" value="" aria-required="true" required="">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <a class="tf-button style-1 w208" href="{{ route('admin.add_product') }}"><i
                        class="icon-plus"></i>Add new</a>
            </div>
            <div class="table-responsive">
                @if(session()->has('success'))
                     <p id="success-message" class="alert alert-success">{{ session()->get('success') }}</p>
                @endif
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>SalePrice</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Featured</th>
                            <th>Stock</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id}}</td>
                            <td class="pname">
                                <div class="image">
                                    <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}" class="image">
                                </div>
                                <div class="name">
                                    <a href="{{ asset('uploads/products/thumbnails' . $product->image) }}" class="body-title-2">{{ $product->name}}</a>
                                    <div class="text-tiny mt-3">{{ $product->slug}}</div>
                                </div>
                            </td>
                            <td>${{ $product->regular_price}}</td>
                            <td>${{ $product->sale_price}}</td>
                            <td>{{ $product->SKU}}</td>
                            <td>{{ $product->category->name}}</td>
                            <td>{{ $product->brand->name}}</td>
                            <td>{{ $product->is_featured == 0 ? "No" : "Yes"}}</td>
                            <td>{{ $product->stock_status}}</td>
                            <td>{{ $product->quantity}}</td>
                            <td>
                                <div class="list-icon-function">
                                    <a href="#" target="_blank">
                                        <div class="item eye">
                                            <i class="icon-eye"></i>
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="item edit">
                                            <i class="icon-edit-3"></i>
                                        </div>
                                    </a>
                                    <form action="{{ route('admin.product_delete', ['id'=>$product->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="item text-danger delete">
                                            <i class="icon-trash-2"></i>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{ $products->links('pagination::bootstrap-5') }}

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Show success message for 3 seconds (3000ms)
    setTimeout(function() {
        let successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.transition = 'opacity 0.5s';
            successMessage.style.opacity = '0';
            setTimeout(() => successMessage.remove(), 500);
        }
    }, 3000);

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.delete').forEach(function (deleteButton) {
            deleteButton.addEventListener('click', function (event) {
                event.preventDefault();
                let form = this.closest('form'); // Find the closest form

                // SweetAlert2 confirmation modal
                Swal.fire({
                    title: "Are You Sure?",
                    text: "You want to delete this brand?",
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonText: "No, cancel!",
                    confirmButtonText: "Yes, delete it!",
                    confirmButtonColor: "#dc3545",
                    cancelButtonColor: "#6c757d"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Submit form if confirmed
                    }
                });
            });
        });
    });
</script>
@endpush

@endsection
