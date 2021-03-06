 <style>
   #mapid { height: 300px;}
 </style>


<div class="row">
    <div class="col-md-12">
<div id="mapid"></div>
<small>Klik lokasi baru yang akan ditambahkan.</small>
<br>
</div>
</div>
<br>
<div class="modal fade" id="modal-default" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Tambah Lokasi</h4>
              </div>
              <div class="modal-body">
                <div class="row">
                <div class="col-md-12">
                  <form id="form_lokasi" method="POST">
                <div class="form-group">
                  <label for="exampleInputEmail1">Nama Lokasi</label>
                  <input type="text" class="form-control nama" id="nama" name="nama" placeholder="Lokasi">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Kuota Pendaftar</label>
                  <input type="number" class="form-control kuota" id="kuota" name="kuota" placeholder="Kuota">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Alamat</label>
                  <input type="text" class="form-control alamat" id="alamat" name="alamat" placeholder="Alamat">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Lattitude</label>
                  <input type="text" class="form-control lat" id="lat" name="lat" readonly placeholder="LAT">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Longtitude</label>
                  <input type="text" class="form-control lng" id="lng" name="lng" readonly placeholder="LNG">
                </div>
</form>
                </div>
              </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-lokasi">Save</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
<table class="table table-bordered">
  <thead>
    <tr>
      <td>No.</td>
      <td>Lokasi</td>
      <td>Kuota</td>
      <td>Alamat</td>
      <td>Username</td>
      <td>Password</td>

      <td>Aksi</td>
    </tr>
  </thead>
  <tbody id="zona_data">

  </tbody>
</table>
<script>
  // $('.btn-tambah').on('click', function() {
  //     $("#modal-default").modal('show');
  // });

  var mymap = L.map('mapid').setView([0.450529, 101.399758], 11);
var marker;
  L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
      '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
      'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    id: 'mapbox.streets'
  }).addTo(mymap);

  mymap.on('click', function(e){
  //mymap.removeLayer(marker);
  $("#modal-default").modal('show');
  var coord = e.latlng;
  var lat = coord.lat;
  var lng = coord.lng;
  if (marker) { // check
        mymap.removeLayer(marker); // remove
    }
  // alert("You clicked the map at latitude: " + lat + " and longitude: " + lng);
  $("input.lat").val(lat);
  $("input.lng").val(lng);
  marker = new L.marker([lat, lng]).addTo(mymap);
  });


    $(".btn-lokasi").on('click', function(e) {
      if ($('input.alamat').val()=='')
    {
      alert("Alamat instansi harus diisi.");
    }
    else if ($('input.nama').val()=='')
    {
      alert("Nama instansi harus diisi.");
    }
    else if ($('input.kuota').val()=='')
    {
      alert("Kuota harus diisi.");
    }
    else {
    var form = $("#form_lokasi").serialize();
  $.ajax({
      type : 'POST',
      url:'modules/kirim_lokasi.php',
      data : form,
      success:function(response)
      {
         if(response == "ok"){
         $("#modal-default").modal('hide');
          alert("Lokasi Terkirim");
          lokasi_list();
         }

         else{
          alert("Gagal Tersimpan")
        }
      },
      error:function()
      {
        alert("Sistem Bermasalah");
      }
    });
}
})

    function lokasi_list()
{
    $.ajax({
      type : 'POST',
      url:'modules/lokasi_list.php',
      success:function(response)
      {
         if(response == "no_data"){
           $("tbody#zona_data").empty();
          $("tbody#zona_data").append("<tr><td colspan='7'><div class='callout callout-danger'>Belum ada data.</div></td></tr>");
         }
         else{
          $("tbody#zona_data").empty();
          $("tbody#zona_data").append(""+response+"");
        }
      },
      error:function()
      {
        alert("Sistem Bermasalah");
      }
    });
  }
  $(function(){ lokasi_list(); });

    $('tbody').on('click', 'button#lokasi', function()
{
var lokasi_lat = $(this).attr('LAT');
var lokasi_lng = $(this).attr('LNG');
if (marker) { // check
        mymap.removeLayer(marker); // remove
    }
marker = new L.marker([lokasi_lat, lokasi_lng]).addTo(mymap);
});



$("tbody").on("click","a.hapus_lokasi", function(){
  var id_lokasi = "ID_LOCATION="+$(this).attr("ID_LOCATION");
  console.log(id_lokasi);
  if(confirm("Seluruh data yang berhubungan dengan data ini tidak akan ditampilkan, Lanjutkan ?"))
  {
  $.ajax({
      type : 'POST',
      url:'modules/hapus_lokasi.php',
      data : id_lokasi,
      success:function(response)
      {
         if(response == "ok"){
          lokasi_list();
         }

         else{
        }
      },
      error:function()
      {
        alert("Sistem Bermasalah");
      }
    });
  }
})
</script>
