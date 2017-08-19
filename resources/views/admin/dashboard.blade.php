@extends('layouts.admin')

@section('adminbody')
    <div id="wrapper">
        <!-- Section -->
        <section>
            <div class="col-md-12">
                <h3>Users</h3>
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
        <!-- end: Section -->
    </div>
    <!-- end: Wrapper -->
@endsection