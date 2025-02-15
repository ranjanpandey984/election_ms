@extends('admin.layouts.admin_master')

@section('content')
    <!-- left column -->
          <div class="col-md-6 ml-auto mr-auto">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add New Candidate</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form>
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Post Name</label>
                    <select name="postname" id="post_name" class="form-control select2bs4" required>
                      <option value="">Select Post Name</option>
                      @foreach ($posts as $post)
                          <option value="{{ $post['id'] }}">{{ $post['post_name'] }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Nepali Name</label>
                    <input type="text" class="form-control" id="nepali_name" name="nepname" id="exampleInputPassword1" placeholder="Enter Nepali Name">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">English Name</label>
                    <input type="text" class="form-control" name="englishname" id="english_name" placeholder="Enter English Name">
                  </div>
                  <div class="row">
                  <div class="col-md-4" style="border-right:1px solid #ddd;">
                    <div id="image-preview"></div>
                  </div>
                  <div class="col-md-4" style="border-right:1px solid #ddd;">
                    <div></div>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="exampleInputPassword1">Photo</label>
                  <input type="file" class="form-control" name="photo" id="examplephoto">
                </div> 
                </div>
                <!-- /.card-body -->

                <div class="card-footer mr-auto ml-auto">
                  <button  class="btn btn-primary crop_image">Submit</button>
                </div>
                
              </form>
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (left) -->
@endsection

@section('script')
<script src="{{ asset('croppieimagecrop/croppie.min.js') }}"></script> 

<script>
 
  $(document).ready(function(){
    $image_crop = $('#image-preview').croppie({
    enableExif:true,
    viewport:{
      width:200,
      height:200,
      type:'square'
    },
    boundary:{
      width:300,
      height:300
    }
  });
  $('#examplephoto').change(function(){
    
    var reader = new FileReader();
    reader.onload = function(event){
      $image_crop.croppie('bind', {
        url:event.target.result
      }).then(function(){
    
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
  });
  $('.crop_image').click(function(event){
   
    var postname = document.getElementById('post_name').value;
    var nepaliname = document.getElementById('nepali_name').value;
    var englishname = document.getElementById('english_name').value; 
    $image_crop.croppie('result', {
      type:'canvas',
      size:'viewport'
    }).then(function(response){
      $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content') } });
      $.ajax({
        type:'POST',
        dataType: "json",
        url:'/candidatecreate',
        data: {'postname':postname,'nepaliname':nepaliname,'englishname':englishname,'image':response,'_token': $('meta[name="_token"]').attr('content'),},
        
        success:function(data)
        {
          window.location.href = "http://localhost:8000/candidatelist";
          var crop_image = '<img src="'+data.path+'" />';
          $('#uploaded_image').html(crop_image);
          console.log(data);
        }
      });
    });
  });
  
});  
</script>
@endsection