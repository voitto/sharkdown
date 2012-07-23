<?php

if (isset($_FILES['md']['tmp_name'])) {
  // save uploaded markdown file
  $allowed = array(
    'your-ip-here'
  );
  if (!in_array($_SERVER['REMOTE_ADDR'])) {
    echo "Sorry, you're not allowed to upload from ".$_SERVER['REMOTE_ADDR'];
    exit;
  }  move_uploaded_file( $_FILES['md']['tmp_name'], "pages/".$_FILES['md']['name'] );
  // redirect the user
  header('Location: http://wiki.hashtag.ly');
  exit;
}

if (isset($_POST['page'])) {
  include_once "lib/markdown.php";
  echo Markdown(file_get_contents('pages/'.$_POST['page'].'.md'));
  exit;
}

// loop over each file in the /pages folder
$pages = "";
$handle = opendir('pages');
while (false !== ($entry = readdir($handle))) {
  // skip files named . and ..
  if (!in_array($entry,array('.','..'))) {
    // split the filename.md into filename and md
    $page = explode('.',$entry);
    $pages .= '<a href="JavaScript:getPage(\''.$page[0].'\')">'.ucwords($page[0])."</a><br>";
  }
}
closedir($handle);

?>
<html>
  <head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript">
      function getPage(page) {
        $.ajax({
          type: "POST",
          url: '/',
          data: {
              'page': page,
          },
          success: function (data) {
            $("#content").html(data)
          },
          complete: function () {
          }
        });
      }
    </script>
  </head>
  <body>
    <div id="links"><?php echo $pages; ?></div>
    <div id="content"></div>
    <form method="post" action="" enctype="multipart/form-data">
      <input type="file" name="md">
      <input type="submit">
    </form>
  </body>
</html>




