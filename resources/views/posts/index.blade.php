@extends('layouts.navbar')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            {{-- Main section --}}
            <main class="main col-md-6 px-2 py-3">

                @forelse ($posts->sortByDesc('created_at') as $post)


                    @php
                        $state=false;
                    @endphp

                    <div class="card mx-auto custom-card mb-5" id="prova">
                        <!-- Card Header -->
                        <div class="card-header d-flex justify-content-between align-items-center bg-white pl-3 pr-1 py-2">
                            <div class="d-flex align-items-center">
                                <a href="/profile/{{$post->user->id}}" style="width: 32px; height: 32px;">
                                    <img src="{{ $post->user->profile->profileImage() }}" class="rounded-circle Card_Header_IMG" style="width: 32px; height: 32px;">
                                </a>
                                <a href="/profile/{{$post->user->id}}" class="my-0 ml-3 text-dark text-decoration-none">
                                    {{ $post->user->username }}
                                </a>
                            </div>
                            <div class="card-dots">
                                <!-- Button trigger modal -->
                                @if($post->user_id==Auth::User()->id)

                                    <button type="button" class="btn btn-link text-muted" data-toggle="modal" data-target="#modal{{$post->id}}">
                                        <i class="fa fa-ellipsis-h"></i>

                                    </button>
                                @endif
                                <!-- Dots Modal -->
                                <div class="modal fade" id="modal{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                    <div class="modal-content">
                                        <ul class="list-group">
                                            <li class="btn list-group-item"><button class="btn" onclick="editCaption({{$post->id}})" id="Editbtn" value="Click" type='button'>Edit caption</button> </li>
                                            {{-- @can('delete', $post) --}}
                                                <form action="/post/delete/{{$post->id}}" method="POST">
                                                    @csrf
                                                    @method("DELETE")
                                                    <li class="btn btn-danger list-group-item">
                                                        <button class="btn" type="submit">Delete</button>
                                                        </li>
                                                </form>
                                            {{-- @endcan --}}
                                            <a href="#"><li class="btn list-group-item" data-dismiss="modal">Cancel</li></a>
                                        </ul>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Image -->
                        <div class="js-post" ondblclick="showLike(this, 'like_{{ $post->id }}')" >
                            <i class="fa fa-heart fa-2x Like" style="color:red"></i>
                            <img class="card-img" src="{{ asset("storage/$post->image") }}" alt="post image" style="max-height: 767px">
                        </div>

                        <!-- Card Body -->
                        <div class="card-body px-3 py-2">

                            <div class="d-flex flex-row">
                                 <form method="POST" action="{{url()->action('LikeController@update2', ['like'=>$post->id])}}">
                                    @csrf
                                    @if (true)
                                        <input id="inputid" name="update" type="hidden" value="1">
                                    @else
                                        <input id="inputid" name="update" type="hidden" value="0">
                                    @endif

                                    @if($post->like->isEmpty())
                                        <button type="submit" class="btn pl-0 likeSubmit" id="likeSubmit{{$post->id}}"  data-attribute="{{ $post->id }}" data-like="{{$post->like}}">
                                            <i class="fa fa-heart-o fa-2x Like" aria-hidden="true"></i>
                                        </button>
                                    @else

                                        @foreach($post->like as $likes)

                                            @if($likes->user_id==Auth::User()->id && $likes->State==true)
                                                @php
                                                    $state=true;
                                                @endphp
                                            @endif

                                        @endforeach




                                         @if($state)
                                            <button type="submit" class="btn pl-0 likeSubmit" id="likeSubmit{{$post->id}}" data-attribute="{{ $post->id }}" data-like="{{$post->like}}" >
                                                <i class="fa fa-heart fa-2x Like" style="color:red"></i>
                                            </button>


                                        @else
                                            <button type="submit" class="btn pl-0 likeSubmit" id="likeSubmit{{$post->id}}" data-attribute="{{ $post->id }}" data-like="{{$post->like}}">
                                                <i class="fa fa-heart-o fa-2x Like" aria-hidden="true"></i>
                                            </button>

                                        @endif

                                    @endif

                                    <button class="btn pl-0 commentIcon"  data-attribute="{{ $post->id }}" >
                                        <i class="fa fa-comment-o fa-2x" aria-hidden="true" ></i>
                                    </button>

                                    <!-- Share Button trigger modal -->
                                    @if ($post->user_id==Auth::User()->id)
                                        <button type="button" class="btn pl-0 pt-1" data-toggle="modal" data-target="#sharebtn{{$post->id}}">
                                            <svg aria-label="Share Post" class="_8-yf5 " fill="#262626" height="22" viewBox="0 0 48 48" width="21"><path d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l15.9 15.6 5.5 22.6c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.4-.4.4-1 .1-1.5zM5.2 6.1h35.5L18 18.7 5.2 6.1zm18.7 33.6l-4.4-18.4L42.4 8.6 23.9 39.7z"></path></svg>
                                        </button>
                                    @endif

                                    <!-- Share Modal -->
                                    <div class="modal fade" id="sharebtn{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                        <div class="modal-content">
                                            <ul class="list-group">
                                                <li class="list-group-item" style="position: absolute; left: -1000px; top: -1000px">
                                                    <input type="text" value="http://localhost:8000/p/{{ $post->id }}" id="copy_{{ $post->id }}" />
                                                </li>
                                                <li class="btn list-group-item" data-dismiss="modal" onclick="copyToClipboard('copy_{{ $post->id }}')">Copy Link</li>
                                                <li class="btn list-group-item" data-dismiss="modal">Cancel</li>
                                            </ul>
                                        </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="flex-row">

                                 <!-- Likes -->
                                @if (count($post->like->where('State',true)) > 1)
                                    <h6 class="card-title" id="likeNumber{{$post->id}}" >

                                        <strong id="LikeCount{{$post->id}}" data-likeCount="{{ count($post->like->where('State',true)) }}">{{ count($post->like->where('State',true)) }} likes</strong>
                                        {{-- @php
                                        echo '<pre> operation : ', print_r($post->like->where('State',true), true) ,'</pre>';
                                    @endphp --}}
                                    </h6>
                                @elseif (count($post->like->where('State',true)) === 1)

                                <h6 class="card-title" id="likeNumber{{$post->id}}" >

                                    <strong id="LikeCount{{$post->id}}" data-likeCount="{{ count($post->like->where('State',true)) }}">{{ count($post->like->where('State',true)) }} like</strong>
                                    {{-- @php
                                    echo '<pre> operation : ', print_r($post->like->where('State',true), true) ,'</pre>';
                                @endphp --}}
                                </h6>
                                @endif
                                {{-- Post Caption --}}

                                <p class="card-text mb-1" id="oldCaption{{$post->id}}" >
                                    <a href="/profile/{{$post->user->username}}" class="my-0 text-dark text-decoration-none">
                                        <strong>{{ $post->user->username }}</strong>
                                    </a>
                                    {{ $post->caption }}
                                </p>

                                <div id="caption_container{{$post->id}}"hidden>
                                                <form method="POST" action="/post/update/{{$post->id}}" enctype="multipart/form-data" class="d-flex align-items-center justify-content-center">
                                                    @csrf
                                                    @method('PATCH')

                                                    <div class=" w-75 mr-3 ">
                                                            <input id="caption{{$post->id}}" type="text" class=" @error('caption') is-invalid @enderror w-100 " name="caption" value="{{$post->caption}} " autocomplete="caption" autofocus>

                                                            @error('caption')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                    </div>
                                                                <button type="submit" class="btn btn-primary">
                                                                    {{ __('Submit') }}
                                                                </button>

                                     </form>

                                </div>



                                <!-- Comment -->
                                 <div class="comments" id="comments{{$post->id}}">
                                    @if (count($post->comments) > 2)
                                    <button class="btn Showbtn" onclick="showComments({{$post->id}})" id="Showbtn" value="Click" type='button'>View all {{count($post->comments)}} comments</button>
                                        {{-- <a href="/p/{{ $post->id }}" class="text-muted">View all {{count($post->comments)}} comments</a> --}}
                                           @endif
                                    <div id="commentWrapper{{$post->id}}">

                                    @foreach ($post->comments->take(3) as $comment)
                                           <p class="ml-3 mb-1"><strong>{{ $comment->user->username }}</strong>  {{ $comment->body }}</p>
                                    @endforeach

                                        </div>


                                </div>

                                <div id="showComments{{$post->id}}"hidden>
                                             @foreach ($post->comments as $comment)
                                                <p class="mb-1 ml-3"><strong>{{ $comment->user->username }}</strong>  {{ $comment->body }}</p>
                                             @endforeach
                                </div>

                                <!-- Created At  -->
                                <p class="card-text text-muted">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="card-footer p-0">

                             <!-- Add Comment -->
                        <form  id="ajaxform">
                                @csrf
                                <div class="form-group mb-0  text-muted">
                                    <div class="input-group is-invalid">
                                        <input type="hidden" name="post_id" value="{{$post->id}}">
                                        <textarea class="form-control addComment" id="body{{$post->id}}" name='body' rows="1" cols="1" placeholder="Add a comment..."value="{{$post->comment}}"></textarea>
                                        <div class="input-group-append">
                                            <button class="btn btn-md btn-outline-info save-data" type="submit" id="{{$post->id}}">Post</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>

                    </div>

                @empty

                    <div class="d-flex justify-content-center p-3 py-5 border bg-white">
                        <div class="card border-0 text-center">
                            <img src="{{asset('img/nopost.png')}}" class="card-img-top" alt="..." style="max-width: 330px">
                            <div class="card-body ">
                                <h3>No Post found</h3>
                                <p class="card-text text-muted">We couldn't find any post, Try to follow someone</p>
                            </div>
                        </div>
                    </div>

                @endforelse

             <!-- Testin Infinite scrooling with vue -->

            </main>

            {{-- Aside Section --}}
            <aside class="aside col-md-4 py-3">
                <div class="position-fixed">

                    <!-- User Info -->
                    <div class="d-flex align-items-center mb-3">
                        <a href="/profile/{{Auth::user()->username}}" style="width: 56px; height: 56px;">
                            <img src="{{ $user->profile->profileImage() }}" class="rounded-circle User_Info_IMG" style="width: 32px; height: 32px;">
                        </a>
                        <div class='d-flex flex-column pl-3'>
                            <a href="/profile/{{Auth::user()->username}}" class='h6 m-0 text-dark text-decoration-none' >
                                <strong>{{ auth()->user()->username }}</strong>
                            </a>
                            <small class="text-muted ">{{ auth()->user()->name }}</small>
                        </div>
                    </div>

                    <!-- Suggestions -->
                    <div class='mb-4' style="width: 300px">
                        <h6 class='text-secondary'>Suggestions For You</h5>

                        <!-- Suggestion Profiles-->
                        {{-- @foreach ($sugg_users as $sugg_user)
                            @if ($loop->iteration == 6)
                                @break
                            @endif
                            <div class='suggestions py-2'>
                                <div class="d-flex align-items-center ">
                                    <a href="/profile/{{$sugg_user->username}}" style="width: 32px; height: 32px;">
                                        <img src="{{ asset($sugg_user->profile->getProfileImage()) }}" class="rounded-circle w-100">
                                    </a>
                                    <div class='d-flex flex-column pl-3'>
                                        <a href="/profile/{{$sugg_user->username}}" class='h6 m-0 text-dark text-decoration-none' >
                                            <strong>{{ $sugg_user->name}}</strong>
                                        </a>
                                        <small class="text-muted">New to Instagram </small>
                                    </div>
                                    <a href="#" class='ml-auto text-info text-decoration-none'>
                                        Follow
                                    </a>
                                </div>
                            </div>
                        @endforeach --}}

                    </div>

                    <!-- CopyRight -->
                    <div>
                        <span style='color: #a6b3be;'>?? 2020 InstaClone from SaraLachgar</span>
                    </div>

                </div>
            </aside>

        </div>
    </div>

@endsection


@section('exscript')
    <script>
//Add Comment

        $(".save-data").click(function(event){
            event.preventDefault();
            const postId = event.currentTarget.attributes.id.nodeValue;

            let body = $("#body"+postId).val();

            let _token   = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/posts/'+postId+'/comment',
                type:"POST",
                data:{
                postId:postId,
                body:body,
                _token: _token
                },
                success:function(response){


                    if (response == 'success') {

                             $.ajax({
                            url: '/posts/'+postId+'/comments',
                            type:"GET",
                            data:{
                            postId:postId,

                            },
                            success:function(response){
                                let output="";
                                $("#body"+postId).val('');
                                $(".Showbtn").hide();


                                console.log(response);


                                 Object.keys(response).forEach(function (comment){
                                //  response.forEach(comment => {
                                //  console.log(comment, response[comment]);
                                //  console.log({
                                //      'output' : response[comment].username
                                //  });
                                      output += '<p class="mb-1"><strong>'+response[comment].username+'</strong> '+response[comment].body+'</p> ';

                                });
                                 $("#commentWrapper"+postId).html(output);
                                 $("#showComments"+postId).html(output);


                            },
                            });


                    }


                },
            });


        })

        // function copyToClipboard(id) {
        //     var copyText = document.getElementById(id);
        //     copyText.select();
        //     copyText.setSelectionRange(0, 99999)
        //     document.execCommand("copy");
        // }

        // function showLike(e, id) {
        //     console.log("Like: ", id);
        //     var heart = e.firstChild;
        //     heart.classList.add('fade');
        //     setTimeout(() => {
        //         heart.classList.remove('fade');
        //     }, 2000);
        // }

        function editCaption(id){
            $("#caption_container"+id)[0].hidden = false;
            $("#oldCaption"+id)[0].hidden = true;
            $('#modal'+id).modal('hide');

        }

        function showComments(id){

            $("#showComments"+id)[0].hidden = false;
            $("#comments"+id)[0].hidden = true;


        }

        $(".commentIcon").on('click', function(id){

            event.preventDefault();
           var postId= $(this).data("attribute");

             $("#body"+postId).focus();
            });

            // $(document).on("click", '.likeSubmit[data-like='$post->like']', function(event) {
         $('.likeSubmit').on('click', function(event) {
            event.preventDefault();
            var postId= $(this).attr("data-attribute");
             var postLike=$(this).attr("data-like");
            var likeCount= $("#LikeCount"+postId).attr("data-likeCount");

             //console.log(likeCount);
             //console.log(postLike);
            //  var parsedPostLike= JSON.parse(postLike);
            //  var state =parsedPostLike[0]?.State;

            //  console.log("initial state " +  state);


            let _token   = $('meta[name="csrf-token"]').attr('content');

                $.ajax({

                        type: "POST",
                        url:  '/like/'+postId,
                        data: {
                        _token: _token
                        },
                        success:function(response){

                            // console.log( response);

                          const state= response[0]?.State;
                         $(this).attr('data-like', state);
                          const likeCount= response[1];
                          const likeNumber= likeCount.length;
                         // console.log(likeNumber);
                          $("#LikeCount"+postId).attr("data-likeCount",likeNumber);

                                     if(state) {

                                         $("#likeSubmit"+postId).html(' <i class="fa fa-heart fa-2x Like" style="color:red"></i>');

                                         if (likeNumber > 1){

                                            // console.log("likeNumber > 1")
                                            $("#likeNumber"+postId).html('<strong id="LikeCount'+postId+'data-likeCount="'+likeCount+'">'+likeNumber+' likes</strong> ');

                                             }else if (likeNumber === 1){
                                            //console.log("likeNumber === 1")
                                            $("#likeNumber"+postId).html('<strong id="LikeCount'+postId+'data-likeCount="'+likeCount+'">'+likeNumber+' like</strong> ');
                                         }


                                        }else {
                                            $("#likeSubmit"+postId).html('<i class="fa fa-heart-o fa-2x Like" aria-hidden="true"></i>' );

                                            if (likeNumber > 1){
                                                //console.log("likeNumber > 1")
                                            $("#likeNumber"+postId).html('<strong id="LikeCount'+postId+'data-likeCount="'+likeCount+'">'+likeNumber+' likes</strong> ');

                                            }else if (likeNumber === 1){
                                            //console.log("likeNumber === 1")
                                            $("#likeNumber"+postId).html('<strong id="LikeCount'+postId+'data-likeCount="'+likeCount+'">'+likeNumber+' like</strong> ');
                                         }




                                        }


                         }

                });
        });
    </script>

    {{-- <script>

        document.addEventListener('submit', function(e){
            e.preventDefault()
            console.log('script run... ');
            var btn = e.submitter;
            console.log(btn.name)

            if (btn.name === 'liked'){
                btn.classList.toggle('text-danger');
                btn.value = !(btn.value == 'true');
            }

        })

            " action="{{url()->action('PostsController@updatelikes', ['post'=>$post->id])}}">
            url =  http://localhost:8000/p/{post}
            (async () => {
                const rawResponse = await fetch('http://localhost:8000/p/', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({a: 1, b: 'Textual content'})
                });
                const content = await rawResponse.json();

                console.log(content);
            })();

    </script> --}}
@endsection





