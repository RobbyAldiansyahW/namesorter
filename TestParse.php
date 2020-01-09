<?php

//Create a function so that we could send a multi dimensional array and sort according to a spesific key while maintain the current index
function array_orderby()
{

    // get the array and comprise it
    $args = func_get_args();
    $data = array_shift($args);

    //Start looping according to the key value
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    //inserting the new array and call function array multisort
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}
//end function

//Reading the txt file from upload
$fp = fopen($_FILES['file']['tmp_name'], 'rb');

//create an array for it
$texts = array();
$i = 0;
    while ( ($text = fgets($fp)) !== false) {

      //because we need to sort it according to the last name and given name we will explode it and create a multidimensional array
      $texts[$i] = explode(" ", $text);
      $last = array_pop($texts[$i]);
      $texts[$i] = array(implode(" ", $texts[$i]), $last);

      $i++;
    }

//Show the list of unsorted name, optional you can delete this if you don't want to display the unsorted name
echo "<p><h3> List of unsorted name according to the txt file.</h3></p>";
foreach($texts as $row) {
    echo implode(" ", $row)."<br/>";
}

// we need to find the key on the array and start to rename it by "given" and "last"
foreach($texts as &$val){

    $val['given'] = $val[0];
    unset($val[0]);
    $val['last'] = $val[1];
    unset($val[1]);
}

//give some space, for a neat output
echo "<br>";
echo "<br>";
echo "<br>";
echo "<p><h3> List of sorted name by their surname, The new name will also written on file <i>sorted-names-list.txt</i> </h3></p>";

//create a trigger for calling the function and return it with sorted names
$sorted = array_orderby($texts, 'last', SORT_ASC, 'given', SORT_ASC);

//Open the new txt file and write the new (sorted) name on it
$myfile = fopen("sorted-names-list.txt", "w") or die("Unable to open file!");

foreach($sorted as $row) {
  // return the exploded array into one string
  // echo it to the user
    echo implode(" ", $row)."<br />";

  //write the text into the sorted-names.txt
    $txt = implode(" ", $row)."\r\n";
    fwrite($myfile, $txt);
}
//close the connection
fclose($myfile);

// And the project is complete.
?>
