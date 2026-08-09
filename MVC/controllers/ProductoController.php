<?php 

    require './repositories/mysql/Database.php';
    require './models/Product.php';

class ProductController{
    private $productModel;
    private $product;
    private $resultado;

    public function __construct(){
        //Aca creo la base de datos para poner obtener el objeto inicial (Instancia de Database)
        $database = new Database(); 

        //Obtenemos una conexion
        $db = $database->getConnection();

        $this->productModel = new Product($db);

    }

    public function read(){
        $products = $this->productModel->getAll();
        include_once './views/home.php';
    }

    public function create(){
        print_r($_POST);
        print($_POST['nombre']??null);
        print($_POST['precio']??null);
        print($_POST['descuento']??null);
        print($_POST['cantidad']??null);

        if( $_SERVER["REQUEST_METHOD"] === "POST"){
            $nombre = $_POST['nombre'];
            $precio = $_POST['precio'];
            $descuento = $_POST['descuento'];
            $cantidad = $_POST['cantidad'];

            //guardando el producto
            $this->productModel->create($nombre, $precio, $descuento, $cantidad);

            //Se direcciona a home.php para mostrar el listado de productos
            header('Location: ./index.php?action=read');
            exit();
        }

        include_once './views/create.php';
    }

    public function update(){
        print($_GET['id']??null);
        $id = $_GET['id']??null ;

        if(!$id){
            echo "id  no especificado";
        }

        $this->product = $this->productModel->getById($id);
        print_r($this->product);

        if(!$this->product){
            echo("Producto no encontrado");
        }

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $precio = $_POST['precio'];
            $descuento = $_POST['descuento'];
            $cantidad = $_POST['cantidad'];

            print_r($precio);
            //guardando el producto
             $this->productModel->update($id,$nombre, $precio, $descuento, $cantidad);

            $this->resultado = "Producto actualizado correctamente.";
            print_r($this->resultado);

            //Se direcciona a home.php para mostrar el listado de productos
            header('Location: ./index.php?action=read');
            exit();
        }


        include_once './views/edit.php';
    }

    public function delete(){
         print($_GET['id']);
         $id = $_GET['id'];

        if(!$id){
            echo "id  no especificado";
            return;
        }
        $this->resultado =  $this->productModel->delete($id);
        if($this->resultado){
            echo "Producto eliminado correctamente.";
        }
         header('Location: ./index.php?action=read');
    }
}

?>