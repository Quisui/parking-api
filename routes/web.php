<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/binary-algorithm', function () {
    // 0 1 0 1 1 0 1 1 1 0 1 1 1 1

    class Binary
    {
        public static function binary($num)
        {
            $i = 0;
            $j = 0;
            $k = 2;
            $numsArr = [];
            while ($i < $num) {
                if ($i == $j) {
                    $numsArr[] = 0;
                    $j = $j + $k;
                    $k += 1;
                } else {
                    $numsArr[] = 1;
                }

                $i += 1;
            }

            print_r($numsArr);
        }
    }

    print_r(Binary::binary(14));
});


Route::get('/pythagorean-theorem', function () {
    function pythag($a, $b)
    {
        $sumOfSquares = pow($a, 2) + pow($b, 2);
        $c = sqrt($sumOfSquares);

        return $c;
    }

    echo ('hypothenus is of the right triangle: ' . pythag(5, 12));
});

Route::get('/area-between-curves', function () {
    function areaCurve($a, $b)
    {
        $sumOfSquares = pow($a, 2) + pow($b, 2);
        $c = sqrt($sumOfSquares);

        return $c;
    }

    echo ('hypothenus is of the right triangle: ' . pythag(5, 12));
});

Route::get('/primer-composite', function () {

    class PrimeOrComposite
    {
        function isPrime($num)
        {
            $i = 1;
            while ($i < $num) {
                $j = $i + 1;
                $prime = true;
                for ($k = 2; $k < $j; $k++) {
                    var_dump([$i, $j, $k, $num]);
                    if (($j % $k) == 0) {
                        $prime = false;
                        break;
                    }
                }

                if ($prime) {
                    echo $j . " is a prime number. \n";
                } else {
                    echo $j . " is a composite number. \n";
                }
                $i++;
            }
        }
    }
    $first = new PrimeOrComposite();
    $first->isPrime(10);
});
