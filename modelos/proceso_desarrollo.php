<?php 
require "../config/Conexion.php";
	Class Proceso_desarrollo {
		public function __construct(){

		}

		public function editarproceso($idproc_desarrollo,$AN_fecha_inicio,$AN_fecha_termino,$AN_estado,$AN_comentario,$DI_fecha_inicio,$DI_fecha_termino,$DI_estado,$DI_comentario,$DE_fecha_inicio,$DE_fecha_termino,$DE_estado,$DE_comentario,$IM_fecha_inicio,$IM_fecha_termino,$IM_estado,$IM_comentario,$MAN_fecha_inicio,$MAN_fecha_termino,$MAN_estado,$MAN_comentario) {
			$sql = "UPDATE proceso_desarrollo SET 
						AN_fecha_inicio='$AN_fecha_inicio',
                                                AN_fecha_termino='$AN_fecha_termino',
                                                AN_estado='$AN_estado',
                                                AN_comentario='$AN_comentario',
                                                DI_fecha_inicio='$DI_fecha_inicio',
                                                DI_fecha_termino='$DI_fecha_termino',
                                                DI_estado='$DI_estado',
                                                DI_comentario='$DI_comentario',
                                                DE_fecha_inicio='$DE_fecha_inicio',
                                                DE_fecha_termino='$DE_fecha_termino',
                                                DE_estado='$DE_estado',
                                                DE_comentario='$DE_comentario',
                                                IM_fecha_inicio='$IM_fecha_inicio',
                                                IM_fecha_termino='$IM_fecha_termino',
                                                IM_estado='$IM_estado',
                                                IM_comentario='$IM_comentario',
                                                MAN_fecha_inicio='$MAN_fecha_inicio',
                                                MAN_fecha_termino='$MAN_fecha_termino',
                                                MAN_estado='$MAN_estado',
                                                MAN_comentario='$MAN_comentario'
					WHERE idproc_desarrollo = '$idproc_desarrollo'";
			return ejecutarConsulta($sql);
		
		}

		public function mostrar($iddesarrollo){
                        $sql = "SELECT pd.*, d.*,p.nombre
                                FROM proceso_desarrollo pd
                                INNER JOIN desarrollo d ON pd.iddesarrollo = d.iddesarrollo
                                INNER JOIN persona p ON d.idcliente = p.idpersona
                                 WHERE d.iddesarrollo='$iddesarrollo'";
                        return ejecutarConsulta($sql);

                    }
                    
	}


 ?>