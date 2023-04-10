<?php

    function fizzbuzz($numero){

        if($numero % 15 == 0) {
            echo "Fizzbuzz";
        
        }else {

            if($numero % 5 == 0){
                echo "Buzz";
            }else {
                if($numero % 3 == 0){
                    echo "Fizz";
                }else {
                    print $numero;
                }
            }
            
        }


    }

    for ($i = 1; $i <= 100; $i++) { 
        fizzbuzz($i);
        echo "<br>";
    }




?>