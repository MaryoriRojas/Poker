<?php

echo "¡Se bienvenido a jugar poker!\n";

$simbolos = ['♥', '♦', '♣', '♠'];
$numeros = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

function crearMazo($simbolos, $numeros) {
    $mazo = [];
    foreach ($simbolos as $simbolo) {
        foreach ($numeros as $numero) {
            $mazo[] = "$numero de $simbolo";
        }
    }
    shuffle($mazo);
    return $mazo;
}

function repartirCartas(&$mazo) {
    $mano = array_splice($mazo, 0, 5);
    return $mano;
}

function mostrarCartas($mano) {
    foreach ($mano as $carta) {
        echo $carta . "\n";
    }
}

function evaluarMano($mano) {
    $numeros = array_map(function($carta) {
        return explode(' ', $carta)[0];
    }, $mano);
    
    $simbolos = array_map(function($carta) {
        return explode(' ', $carta)[2];
    }, $mano);

    $cuentaNumeros = array_count_values($numeros);
    $cuentaSimbolos = array_count_values($simbolos);
    
    $pares = 0;
    $trios = 0;
    $cuatros = 0;
    foreach ($cuentaNumeros as $numero => $cantidad) {
        if ($cantidad == 2) {
            $pares++;
        } elseif ($cantidad == 3) {
            $trios++;
        } elseif ($cantidad == 4) {
            $cuatros++;
        }
    }
    
    if ($cuatros == 1) {
        $cartasPoker = array_filter($mano, function($carta) use ($cuentaNumeros) {
            return $cuentaNumeros[explode(' ', $carta)[0]] == 4;
        });
        return "Poker: " . implode(', ', $cartasPoker);
    } elseif ($trios == 1 && $pares == 1) {
        $cartasFullHouse = array_filter($mano, function($carta) use ($cuentaNumeros) {
            return $cuentaNumeros[explode(' ', $carta)[0]] == 3 || $cuentaNumeros[explode(' ', $carta)[0]] == 2;
        });
        return "Full House: " . implode(', ', $cartasFullHouse);
    } elseif ($trios == 1) {
        $cartasTrio = array_filter($mano, function($carta) use ($cuentaNumeros) {
            return $cuentaNumeros[explode(' ', $carta)[0]] == 3;
        });
        return "Trio: " . implode(', ', $cartasTrio);
    } elseif ($pares == 2) {
        $cartasDosPares = array_filter($mano, function($carta) use ($cuentaNumeros) {
            return $cuentaNumeros[explode(' ', $carta)[0]] == 2;
        });
        return "Dos pares: " . implode(', ', $cartasDosPares);
    } elseif ($pares == 1) {
        $cartasPar = array_filter($mano, function($carta) use ($cuentaNumeros) {
            return $cuentaNumeros[explode(' ', $carta)[0]] == 2;
        });
        return "Par: " . implode(', ', $cartasPar);
    }
    
    $indices = array_map(function($numero) {
        global $numeros;
        return array_search($numero, $numeros);
    }, $numeros);
    sort($indices);
    $escalera = true;
    for ($i = 1; $i < count($indices); $i++) {
        if ($indices[$i] != $indices[$i-1] + 1) {
            $escalera = false;
            break;
        }
    }
    if ($escalera) {
        return "Escalera: " . implode(', ', $mano);
    }

    if (count($cuentaSimbolos) == 1) {
        return "Color: " . implode(', ', $mano);
    }

    return "Carta alta: " . implode(', ', $mano);
}

function mostrarMenu() {
    echo "1. Crear nuevo mazo\n";
    echo "2. Repartir cartas\n";
    echo "3. Evaluar mano\n";
    echo "4. Salir\n";
}

function juego() {
    global $simbolos, $numeros;
    $mazo = [];
    $mano = [];

    while (true) {
        mostrarMenu();
        $opcion = readline("Seleccione una opcion: ");
        
        switch ($opcion) {
            case 1:
                $mazo = crearMazo($simbolos, $numeros);
                echo "Nuevo mazo creado y barajado.\n";
                break;
            case 2:
                $mano = repartirCartas($mazo);
                echo "Cartas repartidas:\n";
                mostrarCartas($mano);
                break;
            case 3:
                if (empty($mano)) {
                    echo "No hay cartas en la mano para evaluar.\n";
                } else {
                    echo "Cartas en la mano:\n";
                    mostrarCartas($mano);
                    $resultado = evaluarMano($mano);
                    echo "Resultado de la evaluación: $resultado\n";
                }
                break;
            case 4:
                echo "Saliendo...\n";
                exit;
            default:
                echo "Opción no válida. Inténtelo de nuevo.\n";
        }
    }
}

juego();