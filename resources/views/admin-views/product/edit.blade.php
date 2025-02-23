@extends('layouts.admin.app')

@section('title', translate('Update product'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('public/assets/admin/css/tags-input.min.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i
                            class="tio-edit"></i> {{translate('product')}} {{translate('update')}}</h1>
                </div>
            </div>
        </div>
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="javascript:" method="post" id="product_form"
                      enctype="multipart/form-data">
                    @csrf
                    @php($language=\App\Model\BusinessSetting::where('key','language')->first())
                    @php($language = $language->value ?? null)
                    @if($language)
                        <ul class="nav nav-tabs mb-4">

                            @foreach(json_decode($language) as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link {{$lang == 'en'? 'active':''}}" href="#" id="{{$lang}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang).'('.strtoupper($lang).')'}}</a>
                                </li>
                            @endforeach

                        </ul>
                        @foreach(json_decode($language) as $lang)
                                <?php
                                if(count($product['translations'])){
                                    $translate = [];
                                    foreach($product['translations'] as $t)
                                    {

                                        if($t->locale == $lang && $t->key=="name"){
                                            $translate[$lang]['name'] = $t->value;
                                        }
                                        if($t->locale == $lang && $t->key=="description"){
                                            $translate[$lang]['description'] = $t->value;
                                        }

                                    }
                                }
                                ?>
                            <div class="card p-4 {{$lang != 'en'? 'd-none':''}} lang_form mb-3" id="{{$lang}}-form">
                                <div class="form-group">
                                    <label class="input-label" for="{{$lang}}_name">{{translate('name')}} ({{strtoupper($lang)}})</label>
                                    <input type="text" {{$lang == 'en'? 'required':''}} name="name[]" id="{{$lang}}_name" value="{{$translate[$lang]['name']??$product['name']}}" class="form-control" placeholder="New Product" >
                                </div>
                                <input type="hidden" name="lang[]" value="{{$lang}}">
                                <div class="form-group pt-4">
                                    <label class="input-label"
                                           for="{{$lang}}_description">{{translate('short')}} {{translate('description')}}  ({{strtoupper($lang)}})</label>
                                    <div id="{{$lang}}_editor" class="min-h-15">{!! $translate[$lang]['description']??$product['description'] !!}</div>
                                    <textarea name="description[]" style="display:none" id="{{$lang}}_hiddenArea"></textarea>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="card p-4" id="english-form">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('name')}} (EN)</label>
                                <input type="text" name="name[]" value="{{$product['name']}}" class="form-control" placeholder="New Product" required>
                            </div>
                            <input type="hidden" name="lang[]" value="en">
                            <div class="form-group pt-4">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('short')}} {{translate('description')}} (EN)</label>
                                <div id="editor" class="min-h-15">{!! $product['description'] !!}</div>
                                <textarea name="description[]" style="display:none" id="hiddenArea"></textarea>
                            </div>
                        </div>
                    @endif
                    <div id="from_part_2">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                           for="exampleFormControlInput1">{{translate('price')}}</label>
                                    <input type="number" value="{{$product['price']}}" min="1" max="100000000" name="price"
                                           class="form-control" step="0.01"
                                           placeholder="Ex : 100" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                           for="exampleFormControlInput1">{{translate('unit')}}</label>
                                    <select name="unit" class="form-control js-select2-custom">
                                        <option value="kg" {{$product['unit']=='kg'?'selected':''}}>
                                            {{translate('kg')}}
                                        </option>
                                        <option value="gm" {{$product['unit']=='gm'?'selected':''}}>
                                            {{translate('gm')}}
                                        </option>
                                        <option value="ltr" {{$product['unit']=='ltr'?'selected':''}}>
                                            {{translate('ltr')}}
                                        </option>
                                        <option value="pc" {{$product['unit']=='pc'?'selected':''}}>
                                            {{translate('pc')}}
                                        </option>
                                        <option value="ml" {{$product['unit']=='ml'?'selected':''}}>
                                            {{translate('ml')}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                        <div class="form-group">
                                        <label class="input-label"
                                                   for="exampleFormControlInput1">{{translate('unit quantity')}}</label>
                                            <input type="number" min="1" max="100000000" step="0.01" value="{{$product['unit_quantity']}}" name="unit_quantity"
                                                   class="form-control"
                                                   placeholder="{{ translate('Ex : 100') }}" required>
                                        </div>
                                    </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                           for="exampleFormControlInput1">{{translate('tax')}}</label>
                                    <input type="number" value="{{$product['tax']}}" min="0" max="100000" name="tax"
                                           class="form-control" step="0.01"
                                           placeholder="Ex : 7" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                           for="exampleFormControlInput1">{{translate('tax')}} {{translate('type')}}</label>
                                    <select name="tax_type" class="form-control js-select2-custom">
                                        <option
                                            value="percent" {{$product['tax_type']=='percent'?'selected':''}}>{{translate('percent')}}
                                        </option>
                                        <option
                                            value="amount" {{$product['tax_type']=='amount'?'selected':''}}>{{translate('amount')}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label class="input-label"
                                           for="exampleFormControlInput1">{{translate('discount')}}</label>
                                    <input type="number" min="0" value="{{$product['discount']}}" max="100000"
                                           name="discount" class="form-control" step="0.01"
                                           placeholder="Ex : 100" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label"
                                           for="exampleFormControlInput1">{{translate('discount')}} {{translate('type')}}</label>
                                    <select name="discount_type" class="form-control js-select2-custom">
                                        <option value="percent" {{$product['discount_type']=='percent'?'selected':''}}>
                                            {{translate('percent')}}
                                        </option>
                                        <option value="amount" {{$product['discount_type']=='amount'?'selected':''}}>
                                            {{translate('amount')}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-4">
                                <div class="form-group">
                                    <label class="input-label"
                                           for="exampleFormControlInput1">{{translate('stock')}}</label>
                                    <input type="number" min="0" max="100000000" value="{{$product['total_stock']}}" name="total_stock" class="form-control"
                                           placeholder="Ex : 100">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                           for="exampleFormControlSelect1">{{translate('category')}}<span
                                            class="input-label-secondary">*</span></label>
                                    <select name="category_id" id="category-id" class="form-control js-select2-custom"
                                            onchange="getRequest('{{url('/')}}/admin/product/get-categories?parent_id='+this.value,'sub-categories')">
                                        @foreach($categories as $category)
                                            <option
                                                value="{{$category['id']}}" {{ $category->id==$product_category[0]->id ? 'selected' : ''}} >{{$category['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-6">
                                <div class="form-group">
                                    <label class="input-label"
                                           for="exampleFormControlSelect1">{{translate('sub_category')}}<span
                                            class="input-label-secondary"></span></label>
                                    <select name="sub_category_id" id="sub-categories"
                                            data-id="{{count($product_category)>=2?$product_category[1]->id:''}}"
                                            class="form-control js-select2-custom"
                                            onchange="getRequest('{{url('/')}}/admin/product/get-categories?parent_id='+this.value,'sub-sub-categories')">

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row"
                             style="border: 1px solid #80808045; border-radius: 10px;padding-top: 10px;margin: 1px">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                           for="exampleFormControlSelect1">{{translate('attribute')}}<span
                                            class="input-label-secondary"></span></label>
                                    <select name="attribute_id[]" id="choice_attributes"
                                            class="form-control js-select2-custom"
                                            multiple="multiple">
                                        @foreach(\App\Model\Attribute::orderBy('name')->get() as $attribute)
                                            <option
                                                value="{{$attribute['id']}}" {{in_array($attribute->id,json_decode($product['attributes'],true))?'selected':''}}>{{$attribute['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2 mb-2">
                                <div class="customer_choice_options" id="customer_choice_options">
                                    @include('admin-views.product.partials._choices',['choice_no'=>json_decode($product['attributes']),'choice_options'=>json_decode($product['choice_options'],true)])
                                </div>
                            </div>
                            <div class="col-md-12 mt-2 mb-2">
                                <div class="variant_combination" id="variant_combination">
                                    @include('admin-views.product.partials._edit-combinations',['combinations'=>json_decode($product['variations'],true)])
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label>{{translate('product')}} {{translate('image')}}</label><small
                                class="color-red">* ( {{translate('ratio')}} 1:1 )</small>
                            <div>
                                <div class="row mb-3">
                                    @foreach(json_decode($product['image'],true) as $img)
                                        <div class="col-3" id="image-{{$loop->index}}">
    <img class="w-100 h-200px"
         src="{{Helpers::onErrorImage(
            $img,
            asset('storage/app/public/product').'/' . $img,
            asset('public/assets/admin/img/160x160/img2.jpg') ,
            'product/')}}" alt="{{ translate('product') }}">
    <button onclick="removeImage({{ $product['id'] }}, '{{ $img }}', {{$loop->index}})" 
            class="btn btn-danger btn-block btn-sm custom-class">{{translate('Remove')}}</button>
</div>
<script>
    function removeImage(productId, imageName, index) {
    if (confirm("Are you sure you want to remove this image?")) {
       $.ajax({
    url: `/admin/product/remove-image/${productId}/${imageName}`, 
    type: 'POST',
    data: {
        _token: '{{ csrf_token() }}',
    },
    success: function(response) {
        if (response.success) {
            $('#image-' + index).remove();
            toastr.success(response.message);
        } else {
            toastr.warning(response.message);
        }
    },
    error: function(xhr) {
        toastr.error('Something went wrong. Please try again.');
    }
});

    }
}

</script>
                                    @endforeach
                                </div>
                                <div class="row" id="coba"></div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script src="{{asset('public/assets/admin/js/spartan-multi-image-picker.js')}}"></script>
    <script src="{{asset('public/assets/admin')}}/js/tags-input.min.js"></script>
    <script src="{{ asset('public/assets/admin/js/quill-editor.js') }}"></script>

    <script>
        "use strict";

        $(".lang_link").click(function(e){
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#"+lang+"-form").removeClass('d-none');
            if(lang == 'en')
            {
                $("#from_part_2").removeClass('d-none');
            }
            else
            {
                $("#from_part_2").addClass('d-none');
            }


        })

        $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'images[]',
                maxCount: 10,
                rowHeight: '215px',
                groupClassName: 'col-3',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('public/assets/admin/img/400x400/img2.jpg')}}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{ translate("Please only input png or jpg type file") }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{ translate("File size too big") }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });

        function getRequest(route, id) {
            $.get({
                url: route,
                dataType: 'json',
                success: function (data) {
                    $('#' + id).empty().append(data.options);
                },
            });
        }

        $(document).ready(function () {
            setTimeout(function () {
                let category = $("#category-id").val();
                let sub_category = '{{count($product_category)>=2?$product_category[1]->id:''}}';
                let sub_sub_category = '{{count($product_category)>=3?$product_category[2]->id:''}}';
                getRequest('{{url('/')}}/admin/product/get-categories?parent_id=' + category + '&&sub_category=' + sub_category, 'sub-categories');
                getRequest('{{url('/')}}/admin/product/get-categories?parent_id=' + sub_category + '&&sub_category=' + sub_sub_category, 'sub-sub-categories');
            }, 1000)
        });

        $(document).on('ready', function () {
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });

        $('#choice_attributes').on('change', function () {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function () {
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
        });

        function add_more_customer_choice_option(i, name) {
            let n = name.split(' ').join('');
            $('#customer_choice_options').append('<div class="row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="' + i + '"><input type="text" class="form-control" name="choice[]" value="' + n + '" placeholder="Choice Title" readonly></div><div class="col-lg-9"><input type="text" class="form-control" name="choice_options_' + i + '[]" placeholder="Enter choice values" data-role="tagsinput" onchange="combination_update()"></div></div>');
            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }

        function combination_update() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '{{route('admin.product.variant-combination')}}',
                data: $('#product_form').serialize(),
                success: function (data) {
                    $('#variant_combination').html(data.view);
                    if (data.length > 1) {
                        $('#quantity').hide();
                    } else {
                        $('#quantity').show();
                    }
                }
            });
        }

        @if($language)
        @foreach(json_decode($language) as $lang)
        var {{$lang}}_quill = new Quill('#{{$lang}}_editor', {
            theme: 'snow'
        });
        @endforeach
        @else
        var en_quill = new Quill('#editor', {
            theme: 'snow'
        });
        @endif

$('#product_form').on('submit', function (e) {
    e.preventDefault(); // Prevent the default form submission
    
    @if($language)
        @foreach(json_decode($language) as $lang)
            var {{ $lang }}_myEditor = document.querySelector('#{{ $lang }}_editor');
            if ({{ $lang }}_myEditor) {
                $("#{{ $lang }}_hiddenArea").val({{ $lang }}_myEditor.children[0].innerHTML);
            }
        @endforeach
    @else
        var myEditor = document.querySelector('#editor');
        if (myEditor) {
            $("#hiddenArea").val(myEditor.children[0].innerHTML);
        }
    @endif

    var formData = new FormData(this);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#product_form').on('submit', function (e) {
    e.preventDefault(); // Prevent the default form submission
    var formData = new FormData(this);
    $.ajax({
        url: '{{ route('admin.product.update', [$product['id']]) }}',
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            console.log('Form success response:', data); // Debug
            if (data.errors) {
                for (var i = 0; i < data.errors.length; i++) {
                    toastr.error(data.errors[i].message);
                }
            } else {
                toastr.success('{{ translate("product updated successfully!") }}');
                setTimeout(function () {
                    location.href = '{{ route('admin.product.list') }}';
                }, 2000);
            }
        },
        error: function (xhr) {
            toastr.error('An unexpected error occurred. Please try again.');
        }
    });
});

});


        function update_qty() {
            var total_qty = 0;
            var qty_elements = $('input[name^="stock_"]');
            for(var i=0; i<qty_elements.length; i++)
            {
                total_qty += parseInt(qty_elements.eq(i).val());
            }
            if(qty_elements.length > 0)
            {
                $('input[name="total_stock"]').attr("readonly", true);
                $('input[name="total_stock"]').val(total_qty);
                console.log(total_qty)
            }
            else{
                $('input[name="total_stock"]').attr("readonly", false);
            }
        }
    </script>
@endpush
