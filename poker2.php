<?php
// Define las cartas y sus valores
$simbolos = ['Corazones', 'Diamantes', 'Tréboles', 'Picas'];
$numeros = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

// Función para crear un mazo de cartas
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

// Función para repartir cartas
function repartirCartas(&$mazo, $numCartas) {
    $mano = array_splice($mazo, 0, $numCartas);
    return $mano;
}

// Función para evaluar una mano
function evaluarMano($mano) {
    $numeros = array_map(function($carta) {
        return explode(' ', $carta)[0];
    }, $mano);
    
    $simbolos = array_map(function($carta) {
        return explode(' ', $carta)[2];
    }, $mano);

    $cuentaNumeros = array_count_values($numeros);
    $cuentaSimbolos = array_count_values($simbolos);
    
    // Verificar pares, tríos y full house
    $pares = 0;
    $trios = 0;
    foreach ($cuentaNumeros as $numero => $cantidad) {
        if ($cantidad == 2) {
            $pares++;
        } elseif ($cantidad == 3) {
            $trios++;
        }
    }
    
    if ($trios == 1 && $pares == 1) {
        return "Full House";
    } elseif ($trios == 1) {
        return "Trío";
    } elseif ($pares == 2) {
        return "Dos pares";
    } elseif ($pares == 1) {
        return "Par";
    }
    
    // Verificar escalera
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
        return "Escalera";
    }

    // Verificar color
    if (count($cuentaSimbolos) == 1) {
        return "Color";
    }

    return "Carta alta";
}

// Función para mostrar el menú
function mostrarMenu() {
    echo "1. Crear nuevo mazo\n";
    echo "2. Repartir cartas\n";
    echo "3. Evaluar mano\n";
    echo "4. Salir\n";
}

// Función principal
function principal() {
    global $simbolos, $numeros;
    $mazo = [];
    $mano = [];

    while (true) {
        mostrarMenu();
        $opcion = readline("Seleccione una opción: ");
        
        switch ($opcion) {
            case 1:
                $mazo = crearMazo($simbolos, $numeros);
                echo "Nuevo mazo creado y barajado.\n";
                break;
            case 2:
                $numCartas = readline("¿Cuántas cartas repartir? ");
                $mano = repartirCartas($mazo, $numCartas);
                echo "Cartas repartidas: " . implode(', ', $mano) . "\n";
                break;
            case 3:
                if (empty($mano)) {
                    echo "No hay cartas en la mano para evaluar.\n";
                } else {
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

principal();