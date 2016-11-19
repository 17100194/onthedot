@extends('layouts.app')

@section('content')
    <script type="text/javascript">

        $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').focus()
        })
    </script>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    @if (count($users) === 0)

                    @elseif (count($users) >= 1)
                        <ul style="list-style: none;">
                        @foreach($users as $user)

                            <li>
                                <div style="padding: 15px;">
                                    <?= $user->name ?>
                                    <br>
                                        <?= $user->campusid ?>
                                        <button class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Schedule Meeting</button>

                                        <div id="myModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    ...
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </li>

                        @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
