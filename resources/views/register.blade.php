<html>
     <head>
          <meta charset="UTF-8">
          <meta name="csrf-token" content="{{ csrf_token() }}" />
          <title>Register</title>
          <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
     
          <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
          <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
     </head>
     <body>
          <div class="container-fluid">
               <div class="row h-100 justify-content-center align-items-center">
                    <div class="col-3">
                         <div class="jumbotron">
                              <h4 class="display-4">Test Page</h4>
                              <p class="lead">Press <code>F12</code> to open developer tools and view responses.</p>
                              <hr class="my-4">
                              <form action="" id="inputForm">                             
                              <div class="form-group">
                                   <label for="inputEmail">Email address</label>
                                   <input type="email" class="form-control" id="inputEmail" aria-describedby="emailHelp" value="john@example.com">                              
                              </div>
                              <div class="form-group">
                                   <label for="inputPassword">Password</label>
                                   <input type="password" class="form-control" id="inputPassword" value="123456789">
                              </div>
                              <button type="button" class="btn btn-primary" id="btn-register">Register</button>
                              <button type="button" class="btn btn-success" id="btn-login">Login</button>
                              </form>
                         </div>
                    </div>
               </div>
          </div>

          <script type="text/javascript">

               $('document').ready(function() {

                    function login() 
                    {
                         var para = {                         
                              'email' : $('#inputEmail').val(),
                              'password' : $('#inputPassword').val()                              
                         };

                         $.ajax('/login', {
                              headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                              type: 'POST',
                              data: para,
                              dataType: 'json',
                              success: function(response) {
                                   console.log(response);
                                   if (response['status'] == true){
                                        alert("Login success. See the token in console.");
                                   }
                              },
                              error: function(error) {
                                   console.log(error.responseJSON);
                              }
                         });
                    }

                    $('#btn-register').on('click', function() {

                         var para = {
                              'name' : 'Mr.John',
                              'email' : $('#inputEmail').val(),
                              'password' : $('#inputPassword').val()                              
                         };

                         jQuery.ajax(this.href, {
                              headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                              type: 'POST',
                              data: para,
                              dataType: 'JSON',                             
                              success: function (data) {                                   
                                   
                                   var para = {
                                        'name' : 'Mr.John',
                                        'email' : $('#inputEmail').val()                                                          
                                   };
                                   
                                   para['code'] = prompt("Check email for verification code and enter below", "");
                                   $.ajax('/confirm', {
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        type: 'POST',
                                        data: para,
                                        dataType: 'JSON',                                     
                                        success: function (data) {
                                             console.log(data);
                                        },
                                        error: function (error) {
                                             console.log(error);
                                        }
                                   });
                                   

                              },
                              error: function (error) {
                                   console.log(error);
                              }
                         });

                    });                   

                    $("#btn-login").on('click', login);
               });               
               
          </script>
     </body>
</html>