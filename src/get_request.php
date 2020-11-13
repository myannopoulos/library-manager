<?php
$isbn = $_POST["ISBN"];
?>

<script type = "module">
    import {key} from './key.js'
    var isbn = "<?php echo $isbn ?>";
    var Http = new XMLHttpRequest();
    var url='https://www.googleapis.com/books/v1/volumes?q=isbn:' + isbn + '&key=' + key;
    Http.open("GET", url);
    Http.send();

    Http.onreadystatechange = function(){
      if(this.readyState==4 && this.status>=200 && this.status<400){
        console.log(Http.responseText)
      }
      else {
          console.log(this.status)
      }
    }
</script>