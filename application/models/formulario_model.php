<?php
    class Formulario_model extends CI_Model{
        
        var $origen                             = '';
        var $edad                               = 0;
        var $sexo                               = '';
        var $tipo                               = '';
        var $mensaje                            = '';
        var $created_at                         = '';
        var $updated_at                         = '';
        var $traba                              = '';
        var $solucion                           = '';
        var $tamano_empresa                     = 0;
        var $servicios_relacionados             = '';
        var $otro_servicio_relacionado          = '';
        var $traba_absurda                      = '';
        var $mail_contacto                      = '';
        var $rut_empresa                        = '';
        var $institucion                        = '';
        var $tramite                            = '';
        var $tipo_tramite                       = '';
        var $etapas_empresa_id                  = null;


        function __construct(){
            parent::__construct();
            $this->created_at = date ("Y-m-d H:i:s");
            $this->updated_at = date ("Y-m-d H:i:s");
        }

        public function graba_formulario($aData){
            $this->origen = $aData['origen'];
            $this->tipo = $aData['tipo'];
            $this->mensaje = isset($aData['mensaje'])?$aData['mensaje']:'';
            $this->edad = isset($aData['edad'])?$aData['edad']:0;
            $this->sexo = isset($aData['sexo'])?$aData['sexo']:'';
            $this->razones_id = isset($aData['razon'])?($aData['razon']==0?null:$aData['razon']):null;
            $this->actividades_id = isset($aData['actividad'])?($aData['actividad']==0?null:$aData['actividad']):null;
            $this->tipo_tramite = isset($aData['tipo_tramite'])?$aData['tipo_tramite']:'';

            //Datos empresa
            $this->traba = isset($aData['traba'])?$aData['traba']:'';
            $this->solucion = isset($aData['solucion'])?$aData['solucion']:'';
            $this->tamano_empresa = isset($aData['tamano_empresa'])?$aData['tamano_empresa']:'';
            $this->servicios_relacionados = isset($aData['servicios_relacionados'])?implode(',',$aData['servicios_relacionados']):'';
            $this->otro_servicio_relacionado = isset($aData['otro_servicio_relacionado'])?$aData['otro_servicio_relacionado']:'';
            $this->traba_absurda = isset($aData['traba_absurda'])?$aData['traba_absurda']:'';
            $this->mail_contacto = isset($aData['mail_contacto'])?$aData['mail_contacto']:'';
            $this->rut_empresa = isset($aData['rut_empresa'])?$aData['rut_empresa']:'';
            $this->etapas_empresa_id = isset($aData['etapa_empresa'])?$aData['etapa_empresa']:null;

            //Datos formulario directo
            $this->institucion = isset($aData['institucion'])?$aData['institucion']:'';
            $this->tramite = isset($aData['tramite'])?$aData['tramite']:'';

            if($this->valida_formulario()){
                $tramite = $this->getTramiteDuplicado($this->origen);
                if(!$tramite){
                    $tramite = array();
                    $tramite['codigo'] = $this->origen;
                    $tramite['nombre'] = $this->tramite;
                    $tramite['institucion'] = $this->url;
                    $tramite['url'] = isset($aData['url_tramite'])?$aData['url_tramite']:'';
                    $tramite['digitalizado'] = 0;
                    $tramite['digitalizacion_proactiva'] = 0;
                    $this->grabaTramite($tramite);
                }
                if(!$aData['mejora']){
                    return $this->db->insert('resultado_digitalizacion', $this);
                }else{
                    return $this->db->insert('resultado_digitalizacion_mejoras', $this);
                }
            }else{
                return false;
            }
        }

        public function valida_formulario(){
            $controller = & get_instance();
            $controller->errorMsg = '';
            if($this->razones_id == 0 && $this->tipo == 'p'){
                $controller->errorMsg = '<p>Debe seleccionar una razón.</p>';
                return false;
            }
            if ($this->origen == '') {
                if($this->institucion == ''){
                    $controller->errorMsg = '<p>Debe ingresar el nombre de la institución a la que pertenece el trámite.</p>';
                }
                if($this->tramite == ''){
                    $controller->errorMsg .= '<p>Debe ingresar el nombre del trámite.</p>';
                }
                if ($this->tipo_tramite == '') {
                    $controller->errorMsg .= '<p>Debe especificar el canal mediante el cual realizó el trámite.</p>';
                }
            }
            if($this->tipo == 'e'){
                if($this->etapas_empresa_id == '' || $this->etapas_empresa_id == 0){
                    $controller->errorMsg .= '<p>Debe seleccionar la etapa en la que se encuentra su Empresa.</p>';
                }
                if($this->tamano_empresa == '' || $this->tamano_empresa == 0){
                    $controller->errorMsg .= '<p>Debe seleccionar el tamaño de su Empresa.</p>';
                }
                if($this->traba == ''){
                    $controller->errorMsg .= '<p>Debe ingresar la denuncia que desea realizar sobre el obstáculo encontrado.</p>';
                }
                if($this->solucion == ''){
                    $controller->errorMsg .= '<p>Debe ingresar la solución propuesta a el obstáculo encontrado.</p>';
                }
                if($this->mail_contacto == ''){
                    $controller->errorMsg .= '<p>Debe ingresar correo de contacto.</p>';
                }elseif(!filter_var($this->mail_contacto, FILTER_VALIDATE_EMAIL)){
                    $controller->errorMsg .= '<p>Debe ingresar un correo válido.</p>';
                }
                if($this->rut_empresa == ''){
                    $controller->errorMsg .= '<p>Debe ingresar el rut de su empresa.</p>';
                }elseif(!$this->validaRut($this->rut_empresa)){
                    $controller->errorMsg .= '<p>Debe ingresar un rut válido para su empresa.</p>';
                }
            }

            if ($controller->errorMsg != '') {
                return false;
            }
            return true;
        }

        public function getActividades(){
            return $this->db->get('actividades')->result();
        }

        public function getRazones(){
            return $this->db->get('razones')->result();
        }

        public function getServiciosRelacionados(){
            return $this->db->get('servicios_relacionados')->result();
        }

        public function getEtapasEmpresa(){
            return $this->db->get('etapas_empresa')->result();
        }

        public function getNombreInstitucion($codigo_institucion){
            $institucion =$this->db->get_where('servicio', array('codigo' => $codigo_institucion))->row();
            return $institucion->nombre;
        }

        public function getInfoFicha($codigo){
            $api_chileatiende = 'https://www.chileatiende.cl/api/fichas/'.$codigo.'?access_token=jYmvOP5znSfNRocw';

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $api_chileatiende);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); //Se debe omitir la verificación del certificado ssl
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));

            $ficha = json_decode(curl_exec($ch), true);

            return isset($ficha['ficha'])?$ficha['ficha']:false;
        }
        public function validaRut($rut){
            $aRut = explode('-', $rut);
            return $aRut[1] == $this->obtieneDV($aRut[0]);
        }
        public function obtieneDV($n){
            $numero = strrev(substr(str_replace(".", "", $n), 0, 8));
            $total = strlen($numero);
            $multiplo = 2;
            $suma = 0;
            for ($i=0;$i<$total;$i++) {
                if ($multiplo > 7) {
                    $multiplo = 2;
                }
                $suma += $numero[$i]*$multiplo;
                $multiplo++;
            }
            $digito = 11-$suma%11;
            if ($digito == 10) {
                $digito = "k";
            }
            return $digito;
        }

        /*BACKEND*/
        public function getResultados($total = false, $filtros = null, $limit = null, $offset = null){
            $tabla_resultados = isset($filtros['solo_mejoras'])&&$filtros['solo_mejoras']?'resultado_digitalizacion_mejoras':'resultado_digitalizacion';

            $sql = 'SELECT rd.*, r.razon AS nombre_razon, a.actividad AS nombre_actividad, ee.nombre AS etapa_empresa';
            $sql .= ', CASE rd.tamano_empresa WHEN 0 THEN "" WHEN 1 THEN "Peque&ntilde;a" WHEN 2 THEN "Mediana" WHEN 3 THEN "Grande" END AS nombre_tamano_empresa';
            $sql .= ' FROM '.$tabla_resultados.' AS rd';
            $sql .= ' LEFT JOIN razones AS r ON r.id = rd.razones_id';
            $sql .= ' LEFT JOIN actividades AS a ON a.id = rd.actividades_id';
            $sql .= ' LEFT JOIN etapas_empresa AS ee ON ee.id = rd.etapas_empresa_id';
            $sql .= ' LEFT JOIN tramites AS t ON t.codigo = rd.origen';
            $order = '';

            if($filtros){
                if(isset($filtros['tipo']) && $filtros['tipo']){
                    $where[] = 'rd.tipo = '.$this->db->escape($filtros['tipo']);
                }
                if(isset($filtros['razon']) && $filtros['razon']) {
                    $where[] = 'rd.razones_id = '.$this->db->escape($filtros['razon']);
                }
                if(isset($filtros['institucion']) && $filtros['institucion']){
                    $where[] = 't.institucion = '.$this->db->escape($filtros['institucion']);
                }
                if(isset($filtros['canal']) && $filtros['canal']){
                    $where[] = 'rd.tipo_tramite = '.$this->db->escape($filtros['canal']);    
                }
                if(isset($filtros['tipo_denuncia']) && $filtros['tipo_denuncia']){
                    $where[] = 'rd.tipo_denuncia = '.$this->db->escape($filtros['tipo_denuncia']);    
                }
                if(isset($filtros['origen']) && $filtros['origen']){
                    $where[] = 'rd.origen = '.$this->db->escape($filtros['origen']);
                }
                if (isset($where)) {
                    $sql .= ' WHERE '.implode(' AND ', $where);
                }
                if(isset($filtros['sort_field']) && $filtros['sort_field']){
                    $order = ' ORDER BY rd.'.$filtros['sort_field'].' '.$filtros['sort_direction'];
                }
            }

            if($total){

                $query = $this->db->query($sql);
                return $query->num_rows();

            }else{

                $sql .= $order;

                if($limit){
                    $sql .= ' LIMIT '.$offset.','.$limit;
                }
                
                $query = $this->db->query($sql);
                return $query->result();

            }            
        }

        public function getResultado($id){
            $sql = 'SELECT rd.*, r.razon AS nombre_razon, a.actividad AS nombre_actividad, ee.nombre AS etapa_empresa';
            $sql .= ', CASE rd.tamano_empresa WHEN 0 THEN "" WHEN 1 THEN "Peque&ntilde;a" WHEN 2 THEN "Mediana" WHEN 3 THEN "Grande" END AS nombre_tamano_empresa';
            $sql .= ' FROM resultado_digitalizacion AS rd';
            $sql .= ' LEFT JOIN razones AS r ON r.id = rd.razones_id';
            $sql .= ' LEFT JOIN actividades AS a ON a.id = rd.actividades_id';
            $sql .= ' LEFT JOIN etapas_empresa AS ee ON ee.id = rd.etapas_empresa_id';
            $sql .= ' WHERE rd.id = '.$this->db->escape($id);

            $query = $this->db->query($sql);
            return $query->row();
        }

        public function idServiciosToNombre($idServicios){
            if($idServicios){
                $sql = 'SELECT nombre FROM servicios_relacionados WHERE id IN ('.$idServicios.')';
                $query = $this->db->query($sql);
                $result = $query->result();
                foreach ($result as $key => $value) {
                    $nombres[] = $value->nombre;
                }
                return implode(', ', $nombres);
            }
            return '';
        }

        public function getOrigenesResultados($filtros = null){
            $sql = 'SELECT DISTINCT origen FROM resultado_digitalizacion';
            $sql .= ' WHERE origen <> "" ';
            if($filtros){
                if(isset($filtros['sin_institucion'])){
                    $sql .= ' AND institucion = ""';
                }
                if(isset($filtros['sin_tramite'])){
                    $sql .= ' AND tramite = ""';
                }
            }
            $sql .= ' ORDER BY origen ASC';
        
            $query = $this->db->query($sql);

            return $query->result();
        }

        public function actualizaInfoResultado($origen, $infoFicha){
            $sql = 'UPDATE resultado_digitalizacion';
            $sql .= ' SET institucion = '.$this->db->escape($infoFicha['servicio']);
            $sql .= ', tramite = '.$this->db->escape($infoFicha['titulo']);
            $sql .= ' WHERE origen = '.$this->db->escape($origen);
            
            return $this->db->query($sql);
        }

        public function getInstituciones($total = false, $order = 'cant_registros', $orderDir = 'DESC', $digitalizados = null, $solo_mejoras = false){

            if ($digitalizados) {
                $sql = 'SELECT t.institucion, COUNT( t.institucion ) AS cant_registros';
                $sql .= ' FROM tramites t';
                $sql .= ' WHERE t.institucion <> ""';
                if($digitalizados){
                    $sql .= ' AND t.sello_chilesinpapeleo = 1';
                }
                $sql .= ' GROUP BY t.institucion';
                $sql .= ' ORDER BY '.$order.' '.$orderDir;
            }else{
                $tabla_resultados = $solo_mejoras?'resultado_digitalizacion_mejoras':'resultado_digitalizacion';

                $sql = 'SELECT t.institucion, COUNT( t.institucion ) AS cant_registros';
                $sql .= ' FROM '.$tabla_resultados.' rd';
                $sql .= ' LEFT JOIN tramites t ON t.codigo = rd.origen';
                $sql .= ' WHERE t.institucion <> ""';
                if($digitalizados){
                    $sql .= ' AND t.sello_chilesinpapeleo = 1';
                }
                $sql .= ' AND origen <> ""';
                $sql .= ' GROUP BY t.institucion';
                $sql .= ' ORDER BY '.$order.' '.$orderDir;
            }

            $query = $this->db->query($sql);
            if($total){
                return $query->num_rows();
            }else{
                return $query->result();
            }
        }

        public function getInstitucionesBackend($options)
        {
            $sql = 'SELECT t.institucion, COUNT( t.institucion ) AS cant_registros';
            $sql .= ' FROM tramites t';
            $sql .= ' WHERE t.institucion <> ""';
            if(isset($options['sello_chilesinpapeleo']) && $options['sello_chilesinpapeleo'])
                $sql .= ' AND t.sello_chilesinpapeleo = 1';

            $sql .= ' GROUP BY t.institucion';
            if(!$options['total'])
                $sql .= ' ORDER BY '.$options["orderby"].' '.$options["orderdir"];

            $query = $this->db->query($sql);
            if($options['total']){
                return $query->num_rows();
            }else{
                return $query->result();
            }
        }

        public function getMaxDenuncias(){
            $sql = 'SELECT COUNT( * ) AS total'
                .' FROM resultado_digitalizacion'
                .' WHERE tramite <>  ""'
                .' GROUP BY institucion, tramite, origen'
                .' ORDER BY total DESC'
                .' LIMIT 0,1';
            $query = $this->db->query($sql);
            $result = $query->result();
            return (int)$result[0]->total;
        }

        public function getTramites($total = false, $options = array(), $order = null, $limit = 40, $offset = 0){
            $razones = $this->getRazones();
            $where = array();

            if(isset($options['institucion'])){
                $where[] = 'MD5(t.institucion) = "'.$options['institucion'].'"';
            }

            if(isset($options['tramite'])){
                $where[] = 't.nombre LIKE "%'.$options['tramite'].'%"';
            }

            if(isset($options['codigo'])){
                $where[] = 't.codigo LIKE "%'.$options['codigo'].'%"';
            }

            if(isset($options['sello_chilesinpapeleo'])){
                $where[] = 'sello_chilesinpapeleo = 1';
            }

            if(isset($options['digitalizados'])){
                $where[] = 'digitalizado = 1';
            }

            if(isset($options['peticiones'])){
                $where[] = 'rd.institucion IS NOT NULL';
            }

            if(isset($options['proactivas'])){
                $where[] = 'rd.institucion IS NULL';
            }

            if (isset($options['fecha_publicacion_guia_online'])) {
                $where[] = 't.fecha_publicacion_guia_online IS NOT NULL';
            }

            if (isset($options['mejoras'])){
                if(intval($options['mejoras']) === 1){
                    $where[] = 'rd.tipo_denuncia = "m"';
                }else{
                    $where[] = 'rd.tipo_denuncia = "o"';
                }
            }

            if (isset($options['periodo'])) {
                $where[] = 'rd.created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)';
            }

            $tabla_resultados = isset($options['solo_mejoras'])&&$options['solo_mejoras']?'resultado_digitalizacion_mejoras':'resultado_digitalizacion';

            if($total){
                $sql = 'SELECT COUNT(*) AS total FROM (';
                $sql .= 'SELECT COUNT(t.codigo) FROM tramites t';
                $sql .= ' LEFT JOIN '.$tabla_resultados.' rd ON rd.origen = t.codigo';
                if($where)
                    $sql .= ' WHERE '.implode(' AND ', $where);
                $sql .= ' GROUP BY t.codigo';
                $sql .= ') as grouping';
            }else{
                $sql = 'SELECT t.nombre AS tramite';
                $sql .= ', t.institucion, t.sello_chilesinpapeleo, t.digitalizado, t.digitalizacion_proactiva, t.codigo AS origen, t.id AS id_tramite, t.url AS url_chileatiende, t.fecha_publicacion_guia_online';
                $sql .= ', COUNT( t.id ) AS cant_registros';
                $sql .= ', COUNT( IF( tipo_tramite =  "oficina", 1, NULL ) ) AS oficina';
                $sql .= ', COUNT( IF( tipo_tramite =  "online", 1, NULL ) ) AS online';
                $sql .= ', COUNT( IF( tipo_tramite =  "correo", 1, NULL ) ) AS correo';
                $sql .= ', COUNT( IF( tipo_tramite =  "telefono", 1, NULL ) ) AS telefono';
                $sql .= ', COUNT( IF( tipo_tramite =  "", 1, NULL ) ) AS nodefinido';

                foreach ($razones as $key => $razon) {
                    $sql .= ', COUNT( IF( razones_id =  '.$razon->id.', 1, NULL ) ) AS razon_'.$razon->id;
                }

                $sql .= ', COUNT( IF( tipo =  "p", 1, NULL ) ) AS personas';
                $sql .= ', COUNT( IF( tipo =  "e", 1, NULL ) ) AS empresas';
            
                // if(isset($options['digitalizados'])){
                
                $sql .= ' FROM tramites t';
                $sql .= ' LEFT JOIN '.$tabla_resultados.' rd ON rd.origen = t.codigo';

                if($where)
                    $sql .= ' WHERE '.implode(' AND ', $where);

                if(isset($options['solo_mejoras']))
                    $sql .= ' AND rd.id IS NOT NULL';

                $sql .= ' GROUP BY t.id';
                
                if($order){
                    foreach ($order as $field => $dir) {
                        $sqlOrder[] = $field.' '.$dir;
                    }
                    $sql .= ' ORDER BY '.implode(',', $sqlOrder);
                }

                $sql .= ' LIMIT '.$offset.','.$limit;
            }

            $query = $this->db->query($sql);

            if($total){
                $result = $query->result();
                return (int)$result[0]->total;
            }else{
                return $query->result();
            }
        }

        public function getTramite($id)
        {
            $sql = 'SELECT *  FROM tramites WHERE id = ?';
            $query = $this->db->query($sql, array($id));

            return $query->row();
        }

        public function getTramiteDuplicado($codigo, $id = null)
        {
            $sql = 'SELECT *  FROM tramites WHERE codigo = ?';
            if($id){
                $sql .= 'AND id <> ?';
                $query = $this->db->query($sql, array($codigo, $id));
            }else{
                $query = $this->db->query($sql, array($codigo));    
            }            

            return $query->row();
        }

        public function actualizaTramite($tramite)
        {
            $sql = 'UPDATE tramites SET';
            $sql .= ' nombre = ?,';
            $sql .= ' institucion = ?,';
            $sql .= ' url = ?,';
            $sql .= ' sello_chilesinpapeleo = ?,';
            $sql .= ' digitalizado = ?,';
            $sql .= ' digitalizacion_proactiva = ?,';
            $sql .= ' codigo = ?';
            $sql .= ' WHERE id = ?';

            return $this->db->query($sql, array($tramite['nombre'], $tramite['institucion'], $tramite['url'], $tramite['sello_chilesinpapeleo'], $tramite['digitalizado'], $tramite['digitalizacion_proactiva'], $tramite['codigo'], $tramite['id']));
        }

        public function grabaTramite($tramite)
        {
            $sql = 'REPLACE INTO tramites (codigo, nombre, institucion, url, sello_chilesinpapeleo, digitalizado, digitalizacion_proactiva)';
            $sql .= ' VALUES (?,?,?,?,?,?,?)';

            return $this->db->query($sql, array($tramite['codigo'], $tramite['nombre'], $tramite['institucion'], $tramite['url'], $tramite['sello_chilesinpapeleo'], $tramite['digitalizado'], $tramite['digitalizacion_proactiva']));
        }

        public function getCantidadPeticionesDigitalizadas()
        {
            $sql = 'SELECT COUNT(t.codigo) as total FROM tramites t';
            $sql .= ' LEFT JOIN resultado_digitalizacion rd ON rd.origen = t.codigo';
            $sql .= ' WHERE rd.id IS NOT NULL AND t.sello_chilesinpapeleo = 1';

            $query = $this->db->query($sql);
            return $query->row()->total;
        }

        public function getCantidadTramitesConPeticiones()
        {
            $sql = 'SELECT COUNT(*) AS total FROM (SELECT t.codigo FROM tramites t';
            $sql .= ' LEFT JOIN resultado_digitalizacion rd ON rd.origen = t.codigo';
            $sql .= ' WHERE rd.tipo_denuncia = "o" AND t.sello_chilesinpapeleo = 1';
            $sql .= ' GROUP BY t.codigo) AS grouping';

            $query = $this->db->query($sql);
            return $query->row()->total;
        }

        public function getTotalDigitalizados()
        {
            $sql = 'SELECT COUNT(*) AS total FROM tramites WHERE sello_chilesinpapeleo = 1';
            $query = $this->db->query($sql);
            return $query->row()->total;
        }

        public function actualizaTipoDenunciaResultadosTramite($tramite)
        {
            if($tramite->origen && $tramite->fecha_publicacion_guia_online){
                $sql = 'UPDATE resultado_digitalizacion SET tipo_denuncia = "m" WHERE created_at > ? AND origen = ?';
                $this->db->query($sql, array($tramite->fecha_publicacion_guia_online, $tramite->origen));
            }
        }
    }
?>