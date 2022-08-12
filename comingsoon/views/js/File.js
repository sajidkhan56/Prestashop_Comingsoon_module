 
 $(document).ready(function() {
  $(".a-date").hide();
  $(".remove").hide();
  $(".cs").on("click",function() {
    var result = document.getElementById('tablehead');
    result.removeAttribute("hidden");
    $(this).closest('.parent-row').find('.a-date').toggle();
    $(".remove").toggle();
  });
});