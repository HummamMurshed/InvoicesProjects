@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection
@section('title')
    الأقسام
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الإعدادت</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ المنتجات</span>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <!-- row -->
    <!-- show success add message -->

    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <!-- End show success add message -->

    <!--  show success Update message -->


    <div class="row">


        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="col-sm-6 col-md-4 col-xl-3">
                        <a class="modal-effect btn btn-outline-primary btn-block"
                           data-effect="effect-scale" data-toggle="modal" href="#modaldemo8">
                            <i class="fas fa-plus ml-1"></i>         إضافة منتج  </a>
                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table key-buttons text-md-nowrap">
                            <thead>
                            <tr>
                                <th class="border-bottom-0">#</th>
                                <th class="border-bottom-0">اسم المنتج</th>
                                <th class="border-bottom-0">اسم القسم</th>
                                <th class="border-bottom-0">الوصف</th>
                                <th class="border-bottom-0">العمليات</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i =0;?>
                            @foreach($allProducts as $product)
                                    <?php $i++;?>
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$product->name}}</td>
                                    <td>{{$product->section->section_name}}</td>
                                    <td>{{$product->description}}</td>
                                    <td>
                                        <button class="btn btn-outline-success btn-sm"
                                                data-name="{{ $product->name }}" data-pro_id="{{ $product->id }}"
                                                data-section_name="{{ $product->section->section_name }}"
                                                data-description="{{ $product->description }}" data-toggle="modal"
                                                data-target="#edit_Product">تعديل</button>

                                        <button class="btn btn-outline-danger btn-sm " data-pro_id="{{ $product->id }}"
                                                data-name="{{ $product->name }}" data-toggle="modal"
                                                data-target="#modaldemo9">حذف</button>


                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal" id="modaldemo8">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">اضافة منتج</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('products.store')}}" method="post">
                            {{csrf_field()}}
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">اسم المنتج </label>
                                    <input type="text" class="form-control" id="name" name="name" required >

                                </div>
                                <div class="form-group">
                                    <label class="my-1 mr-2" for="inlineFormCustomSelectPref">القسم</label>
                                    <select name="section_ID" id="section_id" class="form-control" required>
                                        <option value="" selected disabled> --حدد القسم--</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1"> الوصف </label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>

                            </div>
                            <div class="modal-footer">

                                <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                                <button type="submit" class="btn btn-primary">تاكيد</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- EDIT  modal -->
        <!-- edit -->

        <!-- edit -->
        <div class="modal fade" id="edit_Product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">تعديل المنتج</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action='products/update' method="post">
                        {{ method_field('patch') }}
                        {{ csrf_field() }}
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="title">اسم المنتج :</label>

                                <input type="hidden" class="form-control" name="pro_id" id="pro_id" value="">

                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                            <div class="form-group">
                            <label class="my-1 mr-2" for="inlineFormCustomSelectPref">القسم</label>
                            <select name="section_name" id="section_name" class="custom-select my-1 mr-sm-2" required>
                                @foreach ($sections as $section)
                                    <option>{{ $section->section_name }}</option>
                                @endforeach
                            </select>
                            </div>
                            <div class="form-group">
                                <label for="des">الوصف :</label>
                                <textarea name="description" cols="20" rows="5" id='description'
                                          class="form-control"></textarea>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                            <button type="submit" class="btn btn-primary">تعديل البيانات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End EDit  modal -->

        <!-- delete -->
        <div class="modal fade" id="modaldemo9" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">حذف المنتج</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="products/destroy" method="post">
                        {{method_field('delete')}}
                        {{csrf_field()}}
                        <div class="modal-body">
                            <p>هل انت متاكد من عملية الحذف ؟</p><br>
                            <input type="hidden" name="pro_id" id="pro_id" value="">
                            <input class="form-control" name="name" id="name" type="text" readonly>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                            <button type="submit" class="btn btn-danger">تاكيد</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- End Delete -->

    </div>
    </div>
    </div>

    <!-- main-content closed -->
@endsection

@section('js')
    <!-- Internal Data tables -->
    <!-- Internal Data tables -->
    <script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
    <!--Internal  Datatable js -->
    <script src="{{URL::asset('assets/js/table-data.js')}}"></script>
    <script src="{{URL::asset('assets/js/modal.js')}}"></script>
    <!-- EDit script -->

    <script>
        $('#edit_Product').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var name = button.data('name')
            var section_name = button.data('section_name')
            var pro_id = button.data('pro_id')
            var description = button.data('description')
            var modal = $(this)
            modal.find('.modal-body #name').val(name);
            modal.find('.modal-body #section_name').val(section_name);
            modal.find('.modal-body #description').val(description);
            modal.find('.modal-body #pro_id').val(pro_id);
        });
        <!--End Edit script -->
        <!--Start Delete script -->
        $('#modaldemo9').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var pro_id = button.data('pro_id')
            var name = button.data('name')
            var modal = $(this)

            modal.find('.modal-body #pro_id').val(pro_id);
            modal.find('.modal-body #name').val(name);
        });

    </script>
    <!--End Delete script -->


@endsection





