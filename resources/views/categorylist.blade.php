@extends('layouts.default')
@section('content')        
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Manage Category</h1>
        <ol class="breadcrumb">
            <li><a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('services')}}">Service</a></li>
            <li class="active">Category List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header text-right">
                        <a href="" data-toggle="modal" data-target="#exampleModal" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Category</a>
                    </div>

                    <!-- Flash message -->
                    <div class="alert alert-success" id="alertDiv" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                        <span id="alert"></span>
                    </div>
                    <!-- Flash message -->

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S. No.</th>
                                    <th>Category Name</th>
                                    <th class="text-center">Icon Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($count=1)
                                @if(count($categoryList)>0) 
                                @foreach($categoryList as $index => $val)
                                <tr>
                                    <td >{{ ++$index }}</td>
                                    <td>@if(isset($val->name) && $val->name !== NULL){{ $val->name }} @else None @endif</td>
                                    <td class="text-center"><img src="{{$val->icon_url}}"  alt="{{($val->name)}}" height="35" width="35"></td>
                                    <td>
                                        <a href="" data-toggle="modal"   class="CategoryEdit" name="{{$val->name}}" catid="{{$val->id}}" caturl="{{$val->icon_url}}" data-toggle="tooltip" title="Edit" ><i class="fa fa-pencil"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href='javascript:;' class="ServicesOnCat"  id="{{$val->id}}"  data-toggle="tooltip" title="Services" ><i class="fa fa-bars"></i>&nbsp;&nbsp;&nbsp;</a>                       
                                        <a href='javascript:;' token="{{ csrf_token() }}" class="CategoryDelete" id="{{$val->id}}" data-toggle="tooltip" title="Delete" ><i class="fa fa-trash"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @if($index != 1)<a href="{{ route('category_up', ['id' => $val->id]) }}" data-toggle="tooltip" title="Move UP" <i class="fa fa-arrow-up"></i>&nbsp;&nbsp;&nbsp;</a> @endif
                                        @if($index < count($categoryList))<a href="{{ route('category_down', ['id' => $val->id]) }}" data-toggle="tooltip" title="Move Down" <i class="fa fa-arrow-down"></i>&nbsp;&nbsp;&nbsp;</a> @endif
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- Modal for adding new service category -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel">Add Categories</h4>
                    </div>
                    <form role="form" method="post" action="javascript:;" id="categoryData" enctype="multipart/form-data">
                        <div class="modal-body">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Category Name <i class="requiredInput text-red">*</i></label>
                                        <input type="text" class="form-control" name="name" id="first_name" placeholder="Please enter name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Category Icon Url <i class="requiredInput text-red">*</i></label>
                                        <input type="file" class="form-control" name="url" id="url" >
<!--                                             <input data-preview="#preview" name="input_img" type="file" id="imageInput">-->

                                    </div>
                                </div>
                                <div class="col-md-12 text-info">
                                    <div class="form-group"> 
                                        <b> Note: </b> Image size must be less than 100 KB and Extension should be JPG/JPEG/PNG</div>

                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="<?php if (isset($categoryInfo)) { ?>CategoryEditDataSend<?php } else { ?>CategoryAddDataSend<?php } ?>">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal for editting existing category -->
        <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1">Edit Categories</h4>
                    </div>
                    <form role="form" method="post" action="javascript:;" id="categoryEditData">
                          {{ csrf_field() }}
                        <div class="modal-body">

                            <div class="box-body">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Category Name <i class="requiredInput text-red">*</i></label>
                                        <input type="text" style="display:none" class="form-control" id="edit_id" disabled="" >
                                        <input type="text" class="form-control" name="name"  id="edit_first_name" placeholder="Please enter name">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Category Icon Url <i class="requiredInput text-red">*</i></label>
                                        <input type="hidden" class="form-control" name ="icon_url"  id="edit_url" >
                                        <img class="addimageurl"  height="35" width="35">
                                        <input type="file" class="form-control" name ="icon_urlnew"  id="edit_urlnew" >
                                    </div>
                                </div>
                                <div class="col-md-12 text-info">
                                    <div class="form-group"> 
                                        <b> Note: </b> Image size must be less than 100 KB and Extension should be JPG/JPEG/PNG</div>

                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="CategoryEditDataSend">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.row -->

        <div class="modal fade" id="ServiceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel">List Services</h4>
                    </div>
                    <div class="modal-body" id ="servicebody">
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<script src="{{ asset('/assets/js/customJS/service.js?21') }}" type="text/javascript"></script>
@stop