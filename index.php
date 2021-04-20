<?php 

    function createCsvAsArray($file){ 
        $phones = [];
        while (($row = fgetcsv($file,0, ",")) !== FALSE) {
            
            //RETREIVE MAKE AND MODEL NAME FOR GROUPING
            $make = $row[1];
            $model = $row[2];
            
            //CREATE ARRARY KEY WITH $MAKE.  THIS ENSURE NO UNDEFINED ERROR IS THROWN
            $checkKeyExists = ''; 
            $checkKeyExists = !empty($phones[$make]) ? $phones[$make] : '';

            //REMAINING TARIFF DATA IS PUSHED TO ARRAY
            $tariff = [
                'type' => $row[4], 
                'tar_code' => $row[5], 
                'tar_name' => $row[6], 
                'tar_minutes' => $row[7], 
                'tar_sms' => $row[8], 
                'tar_data' => $row[9]
            ];
            
            $phones[$make][$model][] = $tariff; 
        }
            
        return $phones;
    }


    $file = fopen("phone-data.csv","r");
    $tariffs = createCsvAsArray($file);
    fclose($file);

    
    if(isset($_GET['sort'])) {        
        switch($_GET['sort']){
        case 'a-z':
            ksort($tariffs);
        break;
        case 'z-a':
            krsort($tariffs); 
        break;
        }
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Phone Tariffs</title>

    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="style.css">
    
</head>

<body class="container-fluid">
    <div class="row" style="height:100vh">
        <div class="col col-xl-8 mx-auto pt-3 bg-light">
            <header class="mb-3">
                <h3 class="text-center">Phone Tariffs</h3>
            </header>
            <main>
                
                <div class="rounded border p-3 bg-white">

                    <p>
                        <a href="?sort=a-z">
                            <button class="btn btn-primary">Sort A-Z</button>    
                        </a>
                        <a href="?sort=z-a">
                            <button class="btn btn-primary">Sort Z-A</button>    
                        </a> 
                    </p> 
                
                    <table class="table">
                    <tbody>
                    <?php 
                        foreach($tariffs as $make => $models){
                            
                            echo '<tr>';
                            echo '
                                <td>
                                    <a 
                                        href="javascript:void(0)" 
                                        class="font-weight-bold" 
                                        data-toggle="collapse" 
                                        data-target="#accordion'.$make.'">'.$make.'
                                    </a>
                                </td>

                                <td id="accordion'.$make.'" class="collapse p-0">';
                                    
                                    echo '<table class="table phone-table">';
                                    foreach($models as $model => $tariffs){
                                        echo '<tr class="bg-light"><td class="font-weight-bold">'.$model.'</td></tr>';
                                        echo '<tr><td>';

                                            echo '<table class="table table-sm tariff-table">';
                                            echo '
                                                <tr class="tariff-header">
                                                    <td>Type</td>
                                                    <td>Code</td>
                                                    <td>Name</td>
                                                    <td>Minutes</td>
                                                    <td>SMS</td>
                                                    <td>Data</td>
                                                </tr>';
                                            foreach($tariffs as $key => $value){
                                                echo '
                                                <tr>
                                                    <td>'.$value['type'].'</td>
                                                    <td>'.$value['tar_code'].'</td>
                                                    <td>'.$value['tar_name'].'</td>
                                                    <td>'.$value['tar_minutes'].'</td>
                                                    <td>'.$value['tar_sms'].'</td>
                                                    <td>'.$value['tar_data'].'</td>
                                                </tr>';
                                            }        

                                            echo '</table>';
                                        echo '</td></tr>';
                                    }
                                    echo '</table>';                                
                            echo '</td></tr>';
                        }
                    ?>
                    </tbody>
                </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>