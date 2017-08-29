@extends('layouts.admin')

@section('adminbody')
    <div id="wrapper">
        <!-- Section -->
        <section>
            <div class="col-md-12">
                <h3>Users <span class="right"><button class="btn" data-target="#addUserForm" data-toggle="modal"><i class="fa fa-plus"></i>Add</button></span></h3>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                        <thead class="background-colored text-light">
                        <th class="text-center">#</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Campus ID</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Verified</th>
                        <th class="text-center">Actions</th>
                        </thead>
                        <tbody>
                            @foreach($users as $key=>$user)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->campusid}}</td>
                                    <td>{{$user->email}}</td>
                                    <td><?php if ($user->verified == 1):?><i class="fa fa-check"></i><?php else:?><i class="fa fa-ban"></i><?php endif?></td>
                                    <td><a><i class="fa fa-edit"></i> Edit</a><br><a><i class="fa fa-times"></i> Remove</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$users->render()}}
                </div>
            </div>
        </section>
        <div class="modal fade" id="addUserForm" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="hr-title center">
                            <abbr>New User Form</abbr>
                        </div>
                        <div id="status">

                        </div>
                        <form class="form-transparent-grey">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" id="name" name="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" id="email" name="email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>User Type</label>
                                <select class="form-control" id="type" name="type">
                                    <option value="student">Student</option>
                                    <option value="teacher">Teacher</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark add">Add User</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.add').on('click',function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url: "{{url('/addNewUser')}}",
                data: $('#addUserForm').find('form').serialize(),
                beforeSend: function () {
                    $('.form-group').removeClass('has-error');
                    $('.help-block').remove();
                    $('#status').html('<div class="text-center">Processing</div><img src="<?=asset('public/images/preloader.gif')?>" class="center-block">');
                },
                success: function(data) {
                    $('#status').html(data);
                    if(data.success == false)
                    {
                        var arr = data.errors;
                        $.each(arr, function(index, value)
                        {
                            if (value.length != 0)
                            {
                                $('input[name='+index+']').parent().addClass('has-error');
                                $('input[name='+index+']').after('<span class="help-block"><strong>'+ value +'</strong></span>');
                            }
                        });
                    }
                },
                error: function (xhr, status) {
                    console.log(status);
                    console.log(xhr.responseText);
                }
            });
        });
    </script>
@endsection