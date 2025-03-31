@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Category infomation</h3>
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
                    <a href="{{ route('admin.add_category') }}">
                        <div class="text-tiny">Category</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">New Category</div>
                </li>
            </ul>
        </div>
        <!-- new-category -->
        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.category_store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <fieldset class="name">
                    <div class="body-title">Category Name <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Category name" name="name" tabindex="0" value="{{ old('name') }}" aria-required="true" required="">
                </fieldset>
                @error('name') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                <fieldset class="name">
                    <div class="body-title">Category Slug <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Category Slug" name="slug" tabindex="0" value="{{ old('slug') }}" aria-required="true" required="">
                </fieldset>
                @error('slug') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                <fieldset>
                    <div class="body-title">Upload images <span class="tf-color-1">*</span></div>
                    <div class="upload-image flex-grow">
                        <!-- Image preview container -->
                        <div class="item" id="imgpreview" style="display:none">
                            <img src="" class="effect8" alt="Preview Image">
                        </div>

                        <!-- Upload button -->
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">
                                    Drop your images here or select <span class="tf-color">click to browse</span>
                                </span>
                                <input type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('image') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myFile').change(function (e) {
                let file = this.files[0];
                if (file) {
                    let imgURL = URL.createObjectURL(file);
                    $('#imgpreview img').attr('src', imgURL); // Set the image src
                    $('#imgpreview').show(); // Show the preview div
                }
            });

            $("[name='name']").on('change', function () {
                $("[name='slug']").val(stringToSlug($(this).val()));
            });
        });

        function stringToSlug(Text) {
            return Text.toLowerCase()
                .replace(/\s+/g, '-') // Replace spaces with dashes
                .replace(/[^\w-]+/g, ''); // Remove non-word characters except dashes
        }
    </script>
@endpush
