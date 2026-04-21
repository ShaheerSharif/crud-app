$(document).ready(function () {
  $('#region').on('change', function () {
    const region_id = $(this).val();

    $('#area').val('').prop('disabled', true);
    $('#branch').val('').prop('disabled', true);

    if (region_id !== "") {
      $.post("../utils/get_areas.php", { region_id: region_id }, function (data) {
        $('#area').html(data);
      });

      $('#area').prop('disabled', false);
    }
  });

  $('#area').on('change', function () {
    const area_id = $(this).val();

    $('#branch').val('').prop('disabled', true);

    if (area_id !== "") {
      $.post("../utils/get_branches.php", { area_id: area_id }, function (data) {
        $('#branch').html(data);
      });
      $('#branch').prop('disabled', false);
    }
  });
});
