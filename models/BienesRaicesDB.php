<?php
    namespace Model;
    use PDO;

    abstract class BienesRaicesDB
    {
        // Atributos de clase: Conexión a la base de datos, columnas de tabla y nombre de tabla
        protected static $db;
        protected static $table_name = "";
        protected static $columns = [];
        // Atributos de clase usados en los hijos que cargan imágenes al servidor
        protected static $tmp_img  = ""; // Ubicación temporal de imagen cargada en el servidor
        protected static $old_image_name = ""; // Nombre de imagen a eliminar del server si la base de datos se actualiza
        // Ancho y alto de las imagenes que se insertan en la base de datos
        protected static $img_width = 800;
        protected static $img_height = 600;

        // Conexión de la clase a la base de datos
        public static function openDB()
        {
            self::$db = DB_connect();
        } 

        // Cierre de la conexión
        public static function closeDB()
        {
            self::$db = null;
        }

        // Esta función actualiza los campos del objeto que se correspondan a los introducidos en el array posted
        public function sync(array $posted)
        {
            foreach($posted as $key=>$value)
            {
                if(property_exists($this,$key))
                {
                    $this->$key = $value;
                }
            }
        }

        // Esta función realiza el insert en la base de datos
        public function insert()
        {
            $query = "INSERT INTO ".static::$table_name ." (";
            foreach(static::$columns as $column)
            {
                if($column === "id"): continue; endif; // Id es siempre autoincrement así que para el insert lo quitamos
                $query .= $column.",";
            }
            $query = substr_replace($query,") VALUES (",-1); // La última coma la machacamos
            foreach(static::$columns as $column)
            {
                if($column === "id"): continue; endif;
                $query .= ":".$column.",";
            }
            $query = substr_replace($query," );",-1); // La última coma la machacamos
            $insert_stmnt = self::$db->prepare($query);
            $bind_array = [];
            foreach(static::$columns as $column)
            {
                if($column === "id"): continue; endif;
                $bind_array[":".$column] = $this->$column;
            }
            $insert_stmnt->execute($bind_array);
        }

        // Esta función realiza el update en la base de datos
        public function update()
        {
            $query = "UPDATE ".static::$table_name." SET ";
            foreach(static::$columns as $column)
            {
                if($column === "id"): continue; endif; // Id es siempre autoincrement así que para el insert lo quitamos
                $query .= $column."=:".$column.",";
            }
            $query = substr_replace($query," WHERE id=:id;",-1); // La última coma la machacamos
            
            $update_stmnt = self::$db->prepare($query);
            $bind_array = [];
            foreach(static::$columns as $column)
            {
                $bind_array[":".$column] = $this->$column;
            }
            $update_stmnt->execute($bind_array);
        }

        // Esta función realiza el delete de la base de datos. 
        public function delete()
        {
            $query = "DELETE FROM ".static::$table_name." WHERE id=:id;";
            $delete_stmnt = self::$db->prepare($query);
            $id = static::$columns[0];
            $bind_array = [":id"=>$this->$id]; // El ID siempre es la primera columna
            $delete_stmnt->execute($bind_array);
        }

        // FUNCIONES DE SELECT -> Estas funciones buscan registros en la base de datos, all recupera todos los registros, get recupera un número determinado de registros sin criterio de busqueda y find busca por el atributo seleccionado. Se devuelven arrays que contienen objetos representando cada linea encontrada en la base de datos

        // Todas las clases hijas deberán implementar un método newObject que construya nuevos objetos de ese tipo respetando cada constructor. Necesario para all y find
        abstract protected static function newObject($row);
        public static function all()
        {
            $all = [];
            $query = "SELECT ";
            foreach(static::$columns as $column)
            {
                $query .= $column.",";
            }
            $query = substr_replace($query," FROM ".static::$table_name.";",-1); // La última coma la machacamos
            $select_stmnt = self::$db->prepare($query);
            $select_stmnt->execute();
            $rows = $select_stmnt->fetchAll(PDO::FETCH_ASSOC); 
            foreach($rows as $row)
            {
                $all[] = static::newObject($row);
            }
            return $all;
        }

        public static function get($quantity)
        {
            $all = [];
            $query = "SELECT ";
            foreach(static::$columns as $column)
            {
                $query .= $column.",";
            }
            $query = substr_replace($query," FROM ".static::$table_name." LIMIT ".$quantity.";",-1); // La última coma la machacamos
            $select_stmnt = self::$db->prepare($query);
            $select_stmnt->execute();
            $rows = $select_stmnt->fetchAll(PDO::FETCH_ASSOC); 
            foreach($rows as $row)
            {
                $all[] = static::newObject($row);
            }
            return $all;
        }

        public static function find($column_name,$column_value)
        {
            $found = [];
            $query = "SELECT ";
            foreach(static::$columns as $column)
            {
                $query .= $column.",";
            }
            $query = substr_replace($query," FROM ".static::$table_name." WHERE ".$column_name."=:".$column_name.";",-1); // La última coma la machacamos
            $bind_array = [":".$column_name=>$column_value];
            $select_stmnt = self::$db->prepare($query);
            $select_stmnt->execute($bind_array);
            $rows = $select_stmnt->fetchAll(PDO::FETCH_ASSOC);
            foreach($rows as $row)
            {
                $found[] = static::newObject($row);
            }
            return $found;
        }

    }