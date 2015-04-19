<?php
/******************************************************************************
 *	   BBBB	     AAA    RRRR    BBBB      AAA    ZZZZZZ  U     U  L           *
 *	   B   B	A	A	R	R   B   B	 A   A       ZZ  U     U  L           *
 *	   B   B   A	 A  R	R   B   B	A	  A	    ZZ   U     U  L           *
 *	   BBBB	   A	 A  RRRR    BBBB    A     A     Z    U     U  L           *
 *	   B   B   AAAAAAA  R  R    B   B   AAAAAAA    Z     U     U  L           *
 *     B    B  A     A  R   R   B    B  A     A   ZZ     U     U  L           *
 *     B    B  A     A  R    R  B    B  A     A  ZZ       U   U   L           *
 *     BBBBB   A     A  R    R  BBBBB   A     A  ZZZZZZ    UUU    LLLLLLL     *
 ******************************************************************************/

/**
 * Clase: Lista
 * Descripci�n: Implementaci�n de una Lista Simplemente Enlazada y ordenada.
 * Requisitos: class.Nodo.php
 * Autor: Mat�as Montes
 * Versi�n: 2.1
 */

/******************************************************************************/
/* Release info */
/*--------------*/

# Versi�n 2.0: Se cambi� la implementaci�n a una lista Simplemente enlazada
# Versi�n 1.0: Primer release basado en la Lista Doblemente enlazada.

/******************************************************************************/
/* Librer�as y definiciones requeridas */
/*-------------------------------------*/
require_once("class.Nodo.php");

if (!defined("IGUAL")) define("IGUAL", 0);
if (!defined("MAYOR")) define("MAYOR", 1);
if (!defined("MENOR")) define("MENOR",-1);

function CompararEnteros($DatoEnteroIzq, $DatoEnteroDer)
{
   if ( ($DatoEnteroIzq) == ($DatoEnteroDer) ) $resultado = IGUAL;
   if ( ($DatoEnteroIzq) >  ($DatoEnteroDer) ) $resultado = MAYOR;
   if ( ($DatoEnteroIzq) <  ($DatoEnteroDer) ) $resultado = MENOR;

   return $resultado;
}

class Lista{

/******************************************************************************/
/* Propiedades */
/*-------------*/

	var $primero; 	 // referencia al primer nodo de la lista
    var $comparar;   // nombre de la funci�n que compara los datos almacenados

/******************************************************************************/
/* Interfase (Manejo de bajo nivel) */
/*----------------------------------*/

    /**
	 * Constructor de la Lista
     *
	 * (string) $comparar: El nombre de la funci�n usada para comparar los datos
     * almacenados en la Lista. si el nombre de la funci�n no corresponde a una
     * funci�n v�lida, finaliza la ejecuci�n del script con un mensaje de error.
     * La funci�n mencionada debe tener dos parametros y devolver un valor de
     * comparaci�n de los definidos m�s arriba. De no ser as�, el funcionamiento
     * de la clase puede no ser el esperado.
     * (boolean) $debug_mode: Si es true, se crear� un registro del
     * funcionamiento de la clase.
	 */
    function Lista ($comparar){
    	$this->primero = NULL;
        if (function_exists($comparar))
        	$this->comparar = $comparar;
        else
        	die ("<B>Instanciaci�n err�nea de la clase Lista:</B> al menos uno de sus par�metros no respeta el contrato.");
    } //Fin del constructor

    /**
     * Destructor de la lista
     */
    function destruir(){
    	/* retira uno a uno los nodos de la lista */
        while (! $this->estaVacia() )
        	$this->eliminarNodoPrimero();
    }

    /**
     * Devuelve true si lista esta vacia, sino devuelve false.
     */
    function estaVacia(){
    	return is_null($this->primero);
    } //Fin del m�todo estaVacia

    /**
     * Devuelve la representaci�n de lo siguiente al �ltimo Nodo de la Lista
     */
    function fin(){
    	return NULL;
    } //Fin del metodo fin

    /**
     * Devuelve la referencia al primer nodo de la lista.
     */
    function &primero(){
    	return $this->primero;
    } //Fin del metodo primero

    /**
     * Devuelve el nodo que sigue al pedido
     *
     * (Nodo) &$nodo: Referencia al nodo del que se quiere averiguar el
     * siguiente.
     */
    function &siguiente(&$nodo){
    	if ( ( !$this->estaVacia() ) && ( $nodo != $this->fin() ) && ( get_class($nodo) == "Nodo" ) )
    		return $nodo->siguiente();
        else
        	return $this->fin();
    }//Fin del m�todo siguiente

    /**
     * Devuelve el nodo que antecede al indicado. Si el nodo no est� en la
     * lista, devuelve el �ltimo nodo de la lista.
     * Devuelve fin() si el nodo era el primero o si la lista estaba vac�a.
     *
     * (Nodo) &$nodo: Referencia al nodo del qeu se quiere averiguar el
     * antecesor.
     */
    function &anterior(&$nodo){

    	$previo = $this->fin();
        $cursor =& $this->primero();

        while ( ($cursor != $this->fin() ) && ($cursor != $nodo) )
        {
        	$previo =& $cursor;
            $cursor =& $cursor->siguiente();
        }
        return $previo;
    }

    /**
     * Devuelve la referencia al ultimo nodo de la lista, o devuelve fin() si la
     * lista estuviera vac�a.
     */
    function &ultimo(){
    	//El �ltimo nodo de la lista es el anterior al fin()
        return $this->anterior($this->fin());
    }

    /**
     * Agrega un nodo nuevo al principio de la lista con el dato proporcionado
     * y devuelve una referencia a ese nodo.
     *
     * (mixed)$dato: Elemento a adicionar al principio de la lista
     */
    function &agregarPrimero($dato){
        /* crea el nodo */
        $nuevoNodo = new Nodo($dato);

        /* lo incorpora al principio de la lista */
        $nuevoNodo->setSiguiente( $this->primero() );
        $this->primero =& $nuevoNodo;

        return $nuevoNodo;
    }

    /**
     * Agrega un nodo despu�s del indicado con el dato proporcionado y devuelve
     * una referencia al nodo insertado.
     * Si la lista est� vac�a, agrega el nodo al principio de esta y devuelve
     * la referencia al nodo insertado.
     * Si se el nodo indicado apunta a fin(), no inserta nada y devuelve fin().
     *
     * (mixed) $dato: elemento a adicionar.
     * (Nodo) &$nodo: referencia al nodo despu�s del cual se quiere agregar el
     * dato.
     */
    function &agregarDespues($dato, &$nodo){
    	$nuevoNodo = $this->fin();

        /* si la lista est� vacia se adiciona la principio */
        if ($this->estaVacia())
        	$nuevoNodo =& agregarPrincipio($dato);

        else
        {
        	if ($nodo != $this->fin())
            {
            	/* crea el nodo y lo intercala en la lista */
                $nuevoNodo = new Nodo($dato);
				$nuevoNodo->setSiguiente($nodo->siguiente());
                $nodo->setSiguiente($nuevoNodo);
            }
        }
        return $nuevoNodo;
    }

    /**
     * Agrega un nodo al final de la lista con el dato proporcionado y devuelve
     * una referencia al nodo insertado.
     *
     * (mixed) $dato: elemento a adicionar al final de la lista
     */
    function &agregarUltimo($dato){
    	return $this->agregarDespues($dato, $this->ultimo());
    }

    /**
     * Agrega un nodo con el dato proporcionado antes del indicado y devuelve
     * una referencia al nodo insertado. Si la lista esta vac�a no inserta nada
     * y devuelve fin().
     *
     * (mixed) $dato: Elemento a agregar.
     * (Nodo) &$nodo: Nodo antes del cual se desea agregar el dato.
     */
    function &agregarAntes($dato, &$nodo){
    	$nuevoNodo = $this->fin();

        if (! $this->estaVacia() )
        {
        	if ($nodo != $this->primero() )
            	$nuevoNodo =& $this->agregarDespues($dato, $this->anterior($nodo));
            else
            	$nuevoNodo =& $this->agregarPrimero($dato);
        }
        return $nuevoNodo;
    }

	/**
     * Coloca el dato proporcionado en el nodo indicado.
     *
     * (mixed) $dato: Dato a colocar en el nodo
     * (Nodo) &$nodo: Nodo en el que se colocar� el dato
     */
    function setDato($dato, &$nodo){
    	$nodo->setDato($dato);
    }

    /**
     * Devuelve el contenido de un nodo
     *
     * (Nodo) &$nodo: el nodo del qeu se quiere obtener el contenido.
     */
    function getDato(&$nodo){
    	return $nodo->getDato();
    }
    
    /**
     * elimina el nodo indicado. No realiza acci�n si la lista est� vac�a o si
     * se le indic� fin().
     *
     * (Nodo) &$nodo: Nodo que se desea eliminar
     */
    function eliminarNodo(&$nodo){

        /* verifica que la lista no est� vacia y que nodo no sea fin*/
        if( (! $this->estaVacia() ) && ( $nodo != $this->fin() ) )
        {
        	if ($nodo === $this->primero())
            	$this->primero =& $nodo->siguiente();
            else
            {
            	$previo =& $this->anterior($nodo);
                $previo->setSiguiente($nodo->siguiente());
            }

            unset($nodo);
        }
    }

    /**
     * Si la lista no esta vacia, elimina su nodo primero, sino no realiza
     * acci�n alguna.
     */
    function eliminarNodoPrimero(){
    	if (! $this->estaVacia())
        	$this->eliminarNodo($this->primero());
    }

    /**
     * Si la lista no esta vacia elimina su nodo ultimo, sino no realiza accion.
     */
    function eliminarNodoUltimo(){
    	if (! $this->estaVacia())
        	$this->eliminarNodo($this->ultimo());
    }

/******************************************************************************/
/* Interfase (Principal) */
/*-----------------------*/

	/**
     * Si el dato se encuentra en la lista, devuelve una referencia al primer
     * nodo que lo contiene. Si el dato no se encuentra en la lista devuelve
     * fin().
     *
     * (mixed) $dato: Elemento a localizar.
     */
    function &localizarDato($dato){
    	$encontrado = false;
        $cursor =& $this->primero();
        $comparar = $this->comparar;

        //recorre los nodos hasta llegar al �ltimo o hasta encontrar el buscado
        while ( ($cursor != $this->fin()) && (!$encontrado) )
        {
        	/* obtiene el dato del nodo y lo compara */
            $datoCursor = $cursor->getDato();

            if ( $comparar($datoCursor, $dato) == IGUAL )
            	$encontrado = true;
            else
            	$cursor =& $cursor->siguiente();
        }

        /* si no lo encontr� devuelve fin */
        if (! $encontrado)
        	$cursor = $this->fin();

        return $cursor;
    }
	
	/**
     * Busca un dato, devuelve true o false
     *
     * (mixed) $dato: Elemento a localizar.
     */
    function buscarDato($dato){
    	$encontrado = false;
        $cursor =& $this->primero();
        $comparar = $this->comparar;

        //recorre los nodos hasta llegar al �ltimo o hasta encontrar el buscado
        while ( ($cursor != $this->fin()) && (!$encontrado) )
        {
        	/* obtiene el dato del nodo y lo compara */
            $datoCursor = $cursor->getDato();

            if ( $comparar($datoCursor, $dato) == IGUAL )
            	$encontrado = true;
            else
            	$cursor =& $cursor->siguiente();
        }

        return $encontrado;
    }	

    /**
     * Agrega a la lista el dato manteniendo el orden pero con multiples
     * valores iguales y devuelve una referencia al nodo insertado.
     *
     * (mixed) $dato: Elemento a insertar
     */
    function &insertarDato($dato){
    	$previo =& $this->primero();
        $cursor =& $this->primero();
        $ubicado = false;
        $comparar = $this->comparar;

        /* recorre la lista buscando el lugar de la inserci�n */
        while ( ($cursor != $this->fin()) && (! $ubicado) )
        {
        	$datoCursor = $cursor->getDato();
			if ($comparar($datoCursor, $dato) == MAYOR)
            	$ubicado = true;
            else
            {
            	$previo =& $cursor;
                $cursor =& $cursor->siguiente();
            } //END IF
        } //END WHILE

        if ($cursor === $this->primero())
        	$nuevoNodo =& $this->agregarPrimero($dato);
        else
        	$nuevoNodo =& $this->agregarDespues($dato, $previo);

        return $nuevoNodo;
    }

    /**
     * Elimina el dato de la lista, si el mismo se encuentra.
     *
     * (mixed) dato: elemento a eliminar.
     */
    function eliminarDato($dato){
    	/* localiza el dato y luego lo elimina */
        $nodo =& $this->localizarDato($dato);
        if ($nodo != $this->fin())
        	$this->eliminarNodo($nodo);
    } //Fin del m�todo eliminarDato

    /**
     * Devuelve la cantidad de elementos que contiene la lista
     */
    function cantidadElementos(){
    	if ($this->estaVacia())
        	return 0;
        else
        {
        	$cantidad = 0;
            $cursor =& $this->primero();
            while($cursor != $this->fin())
            {
            	$cantidad++;
                $cursor =& $this->siguiente($cursor);
            } //END WHILE
            return $cantidad;
        } //END IF
    } //Fin del m�todo cantidadElementos.
}
?>