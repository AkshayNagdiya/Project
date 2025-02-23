@extends('layouts.admin.app')

@section('title', translate('Add new product'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('public/assets/admin/css/tags-input.min.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/product.png')}}" alt="{{ translate('product') }}">
                {{translate('add_new_product')}}
            </h2>
        </div>


        <div class="row">
            <div class="col-12">
                <form action="javascript:" method="post" id="product_form"
                      enctype="multipart/form-data">
                    @csrf
                    @php($language=\App\Model\BusinessSetting::where('key','language')->first())
                    @php($language = $language->value ?? null)
                    @php($default_lang = 'bn')
                    @if($language)
                        @php($default_lang = json_decode($language)[0])
                        <ul class="nav nav-tabs mb-4 max-content">

                            @foreach(json_decode($language) as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link {{$lang == $default_lang? 'active':''}}" href="#" id="{{$lang}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang).'('.strtoupper($lang).')'}}</a>
                                </li>
                            @endforeach

                        </ul>
                        @foreach(json_decode($language) as $lang)
                            <div class="card mb-3 card-body {{$lang != $default_lang ? 'd-none':''}} lang_form" id="{{$lang}}-form">
                                <div class="form-group">
                                    <label class="input-label" for="{{$lang}}_name">{{translate('name')}} ({{strtoupper($lang)}})</label>
                                    <input type="text" {{$lang == $default_lang? 'required':''}} name="name[]" id="{{$lang}}_name" class="form-control" placeholder="{{ translate('New Product') }}" oninvalid="document.getElementById('en-link').click()">
                                </div>
                                <input type="hidden" name="lang[]" value="{{$lang}}">
                                <div class="form-group pt-4">
                                    <label class="input-label"
                                           for="{{$lang}}_description">{{translate('short')}} {{translate('description')}}  ({{strtoupper($lang)}})</label>
                                    <div id="{{$lang}}_editor"></div>
                                    <textarea name="description[]" style="display:none" id="{{$lang}}_hiddenArea"></textarea>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="card p-4" id="{{$default_lang}}-form">
                            <div class="form-group">
                                <label class="input-label">{{translate('name')}} (EN)</label>
                                <input type="text" name="name[]" class="form-control" placeholder="{{ translate('new_product') }}" required>
                            </div>
                            <input type="hidden" name="lang[]" value="en">
                            <div class="form-group pt-4">
                                <label class="input-label">{{translate('short')}} {{translate('description')}} (EN)</label>
                                <div id="editor"></div>
                                <textarea name="description[]" style="display:none" id="hiddenArea"></textarea>
                            </div>
                        </div>
                    @endif

                    <div id="from_part_2">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                   for="exampleFormControlInput1">{{translate('price')}}</label>
                                            <input type="number" min="1" max="100000000" step="0.01" value="1" name="price"
                                                   class="form-control"
                                                   placeholder="{{ translate('Ex : 100') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                   for="exampleFormControlInput1">{{translate('unit')}}</label>
                                            <select name="unit" class="form-control js-select2-custom">
                                                <option value="kg">{{translate('kg')}}</option>
                                                <option value="gm">{{translate('gm')}}</option>
                                                <option value="ltr">{{translate('ltr')}}</option>
                                                <option value="pc">{{translate('pc')}}</option>
                                                <option value="ml">{{translate('ml')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="form-group">
                                        <label class="input-label"
                                                   for="exampleFormControlInput1">{{translate('unit quantity')}}</label>
                                            <input type="number" min="1" max="100000000" step="0.01" value="1" name="unit_quantity"
                                                   class="form-control"
                                                   placeholder="{{ translate('Ex : 100') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                   for="exampleFormControlInput1">{{translate('tax')}}</label>
                                            <input type="number" min="0" value="0" step="0.01" max="100000" name="tax"
                                                   class="form-control"
                                                   placeholder="{{ translate('Ex : 7') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                   for="exampleFormControlInput1">{{translate('tax')}} {{translate('type')}}</label>
                                            <select name="tax_type" class="form-control js-select2-custom">
                                                <option value="percent">{{translate('percent')}}</option>
                                                <option value="amount">{{translate('amount')}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                   for="exampleFormControlInput1">{{translate('discount')}}</label>
                                            <input type="number" min="0" max="100000" value="0" step="0.01" name="discount" class="form-control"
                                                   placeholder="{{ translate('Ex : 100') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                   for="exampleFormControlInput1">{{translate('discount')}} {{translate('type')}}</label>
                                            <select name="discount_type" class="form-control js-select2-custom">
                                                <option value="percent">{{translate('percent')}}</option>
                                                <option value="amount">{{translate('amount')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                   for="exampleFormControlInput1">{{translate('stock')}}</label>
                                            <input type="number" min="0" max="100000000" value="0" name="total_stock" class="form-control"
                                                   placeholder="{{ translate('Ex : 100') }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                   for="exampleFormControlSelect1">{{translate('category')}}<span
                                                    class="input-label-secondary">*</span></label>
                                            <select name="category_id" class="form-control js-select2-custom"
                                                    onchange="getRequest('{{url('/')}}/admin/product/get-categories?parent_id='+this.value,'sub-categories')">
                                                <option value="">---{{translate('select category')}}---</option>
                                                @foreach($categories as $category)
                                                    <option value="{{$category['id']}}">{{$category['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                   for="exampleFormControlSelect1">{{translate('sub_category')}}<span
                                                    class="input-label-secondary"></span></label>
                                            <select name="sub_category_id" id="sub-categories"
                                                    class="form-control js-select2-custom"
                                                    onchange="getRequest('{{url('/')}}/admin/product/get-categories?parent_id='+this.value,'sub-sub-categories')">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="input-label">
                                                {{translate('select_attributes')}}
                                                <span class="input-label-secondary"></span>
                                            </label>
                                            <select name="attribute_id[]" id="choice_attributes"
                                                    class="form-control js-select2-custom"
                                                    multiple="multiple">
                                                @foreach(\App\Model\Attribute::orderBy('name')->get() as $attribute)
                                                    <option value="{{$attribute['id']}}">{{$attribute['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="customer_choice_options mb-4" id="customer_choice_options"></div>
                                        <div class="variant_combination mb-4" id="variant_combination"></div>
                                        <div>
                                            <div class="mb-2">
                                                <label class="text-capitalize">{{translate('product_image')}}</label>
                                                <small class="text-danger"> * ( {{translate('ratio')}} 1:1 )</small>
                                            </div>
                                            <div class="row" id="coba"></div>
                                        </div>
                                        <div class="d-flex justify-content-end gap-3">
                                            <button type="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                                            <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
            if(lang == '{{$default_lang}}')
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
                groupClassName: 'col-auto',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset("public/assets/admin/img/400x400/img2.jpg")}}',
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
        var en_quill = new Quill('#{{$lang}}_editor', {
            theme: 'snow'
        });
        @endforeach
        @else
        var bn_quill = new Quill('#editor', {
            theme: 'snow'
        });
        @endif

        $('#product_form').on('submit', function () {
            @if($language)
            @foreach(json_decode($language) as $lang)
            var {{$lang}}_myEditor = document.querySelector('#{{$lang}}_editor')
            $("#{{$lang}}_hiddenArea").val({{$lang}}_myEditor.children[0].innerHTML);
            @endforeach
            @else
            var myEditor = document.querySelector('#editor')
            $("#hiddenArea").val(myEditor.children[0].innerHTML);
            @endif
            var formData = new FormData(this);
            console.log(formData);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.product.store')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{ translate("product uploaded successfully!") }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href = '{{route('admin.product.list')}}';
                        }, 2000);
                    }
                }
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
            }
            else{
                $('input[name="total_stock"]').attr("readonly", false);
            }
        }
    </script>
@endpush
