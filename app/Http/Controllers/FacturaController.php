<?php

namespace App\Http\Controllers;
use App\Models\Usuario; 
use App\Models\Empresa; 
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\NumeroComprobante;

class FacturaController extends Controller
{
    
    public function index()
    {
        return response()->json(Factura::with(['cliente', 'proveedor', 'usuario', 'detalles'])->get(), 200);
    }

    /**
     * Almacena una nueva factura.
     */
    public function store(Request $request)
{
   
    $validator = Validator::make($request->all(), [
        'empresa_id' => 'required|exists:empresas,id',
        'cliente_id' => 'nullable|exists:clientes,id',
        'proveedor_id' => 'nullable|exists:proveedors,id',
        'usuario_id' => 'required|exists:usuarios,id',
        'fecha_emision' => 'required|date',
        'fecha_vencimiento' => 'nullable|date',
        'total' => 'required|numeric',
        'tipo' => 'required|in:venta,compra',
        'estado' => 'in:Emitida,Pagada,Cancelada',
        'xml_data' => 'nullable|string',
        'detalles' => 'required|array', // Asegúrate de que detalles es un array
        'detalles.*.codigo_cabys' => 'required|string',
        'detalles.*.cantidad' => 'required|numeric',
        'detalles.*.descripcion' => 'required|string',
        'detalles.*.precio_unitario' => 'required|numeric',
        'detalles.*.subtotal' => 'required|numeric',
        'detalles.*.totalIVA' => 'required|numeric',
        'detalles.*.totalVenta' => 'required|numeric',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Crear la instancia de usuario para obtener la cédula de la empresa
    // Obtener el usuario por su ID
$usuario = Usuario::find($request->usuario_id);

if ($usuario && $usuario->empresa) {
    // Obtener la cédula de la empresa a partir de la relación
    $cedula_empresa = $usuario->empresa->cedula_empresa; // Asegúrate de que el campo cedula_empresa existe en el modelo Empresa
} else {
    // Manejar el caso en que el usuario o la empresa no existen
    $cedula_empresa = null; // O maneja el error como prefieras
}


    // Generar el código único y el número de comprobante
    $codigoData = $this->generarCodigoUnico(001, 001, 04, $cedula_empresa);

    // Crear la factura con el código único y el número de comprobante
    $factura = Factura::create(array_merge($request->all(), [
        'codigo_unico' => $codigoData['codigo_unico'], 
        'numero_comprobante' => $codigoData['numero_comprobante'] // Asegúrate de que la columna exista en tu tabla
    ]));

    // Generar el XML
    $xmlData = $this->generateXml($factura , $request->detalles);
    $factura->xml_data = $xmlData;
    $factura->save(); // Actualiza la factura con el XML

    

    return response()->json($factura, 201);
}

    /**
     * Muestra una factura específica.
     */
    public function show($id)
    {
        $factura = Factura::with(['cliente', 'proveedor', 'usuario', 'detalles'])->find($id);

        if (!$factura) {
            return response()->json(['message' => 'Factura no encontrada'], 404);
        }

        return response()->json($factura, 200);
    }

    /**
     * Actualiza una factura existente.
     */
    public function update(Request $request, $id)
    {
        $factura = Factura::find($id);

        if (!$factura) {
            return response()->json(['message' => 'Factura no encontrada'], 404);
        }

        // Validación de datos
        $validator = Validator::make($request->all(), [
            'cliente_id' => 'nullable|exists:clientes,id',
            'proveedor_id' => 'nullable|exists:proveedors,id',
            'usuario_id' => 'required|exists:usuarios,id',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'nullable|date',
            'total' => 'required|numeric',
            'tipo' => 'required|in:venta,compra',
            'estado' => 'in:Emitida,Pagada,Cancelada',
            'codigo_unico' => 'required|string|unique:faturas,codigo_unico,' . $id,
            'xml_data' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Actualizar la factura
        $factura->update($request->all());
        $detalles = $request->detalles;
        // Generar el XML nuevamente si es necesario
        $xmlData = $this->generateXml($factura);
        $factura->xml_data = $xmlData;
        $factura->save(); // Actualiza la factura con el nuevo XML

        return response()->json($factura, 200);
    }

    /**
     * Elimina una factura existente.
     */
    public function destroy($id)
    {
        $factura = Factura::find($id);

        if (!$factura) {
            return response()->json(['message' => 'Factura no encontrada'], 404);
        }

        $factura->delete();
        return response()->json(['message' => 'Factura eliminada correctamente'], 204);
    }
    function generarNumeroConsecutivoTiquete($codigoPais = '001', $codigoSucursal = '0001', $puntoVenta = '01', $tipoComprobante = '04') {
        // Obtener el último número de comprobante desde la base de datos
        $numeroComprobante = NumeroComprobante::first();
        
        if (!$numeroComprobante) {
            // Si no existe, crear uno nuevo con el último número en 1
            $numeroComprobante = NumeroComprobante::create(['ultimo_numero' => 1]);
            $ultimoNumero = 1;
        } else {
            // Guardar el último número
            $ultimoNumero = $numeroComprobante->ultimo_numero;
        }

        // Formateamos cada componente al tamaño requerido
        $codigoPaisFormateado = str_pad($codigoPais, 3, '0', STR_PAD_LEFT);         // 3 dígitos
        $codigoSucursalFormateado = str_pad($codigoSucursal, 4, '0', STR_PAD_LEFT);  // 4 dígitos
        $puntoVentaFormateado = str_pad($puntoVenta, 2, '0', STR_PAD_LEFT);          // 2 dígitos
        $tipoComprobanteFormateado = str_pad($tipoComprobante, 2, '0', STR_PAD_LEFT); // 2 dígitos
        $consecutivoFormateado = str_pad($ultimoNumero, 8, '0', STR_PAD_LEFT);        // 8 dígitos

        // Incrementar el consecutivo para la próxima llamada
        $nuevoNumero = $ultimoNumero + 1;

        // Actualizar el número consecutivo en la base de datos
        $numeroComprobante->ultimo_numero = $nuevoNumero;
        $numeroComprobante->save();

        //se concaten los componentes #comprobante 20 dígitos
        return $codigoPaisFormateado . $codigoSucursalFormateado . $puntoVentaFormateado . $tipoComprobanteFormateado . $consecutivoFormateado;
    }

    /**
     * Genera un código único de 50 caracteres para la factura.
     */
   

     private function generarCodigoUnico($sucursal, $terminal, $tipo, $cedula_empresa) {
        // Asegúrate de cargar la relación del usuario
      
    
        $pais = "506"; // Código del país, Costa Rica
        $fecha = now()->format('dmy'); // Cambiado a 'dmyHis' para incluir solo los últimos dos dígitos del año
    
        $situacion = "1";
       
    
        $codigoSeguridad = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);

    
        // Llamada a la función generarNumeroConsecutivoTiquete con el valor actual de consecutivo
        $numeroConsecutivo = $this->generarNumeroConsecutivoTiquete("001", $sucursal, $terminal, $tipo);
    
        // Construir el código único incluyendo la cedula_empresa
        $codigoUnico = $pais . $fecha . $cedula_empresa . $numeroConsecutivo .$situacion. $codigoSeguridad;
    
        // Formato a 50 caracteres
        $codigoUnicoFormateado = str_pad($codigoUnico, 50, '0');
    
        // Aquí puedes definir cómo se genera el número de comprobante
        $numeroComprobante = $numeroConsecutivo;
    
        return [
            'codigo_unico' => $codigoUnicoFormateado,
            'numero_comprobante' => $numeroComprobante,
        ];
    }
    
    

    /**
     * Genera el XML para la factura.
     */
    private function generateXml($factura, $detallesFactura) {
        // Asegúrate de cargar las relaciones del usuario, cliente, y los detalles de la factura
        $factura->load(['usuario', 'detalles']);
        
        $xml = new \SimpleXMLElement('<factura/>');
        $xml->addChild('codigo_unico', $factura->codigo_unico);
        $xml->addChild('codigo_actividad', "552004"); // Este puede ser un valor estático o dinámico según tu requerimiento
        $xml->addChild('NumeroComprobante', $factura->numero_comprobante);
        $xml->addChild('fecha_emision', $factura->fecha_emision);
        $xml->addChild('nombre', $factura->usuario->empresa->nombre ?? '');
    
        // Agregar la información de la empresa
        $tipo_cedula = $factura->usuario->empresa->empresa;
        $xml->addChild('tipo_cedula', $tipo_cedula === 'fisica' ? '1' : ($tipo_cedula === 'juridica' ? '2' : ''));
        $xml->addChild('cedula_empresa', $factura->usuario->empresa->cedula_empresa ?? '');
        $xml->addChild('provincia', $factura->usuario->empresa->provincia ?? '');
        $xml->addChild('canton', $factura->usuario->empresa->canton ?? '');
        $xml->addChild('distrito', $factura->usuario->empresa->distrito ?? '');
        $xml->addChild('otras_senas', $factura->usuario->empresa->otras_senas ?? '');
        $xml->addChild('pais', "506");  // País (por ejemplo, Costa Rica)
        $xml->addChild('telefono', $factura->usuario->empresa->telefono ?? '');
        $xml->addChild('correo_electronico', $factura->usuario->empresa->correo ?? '');
    
        // Información del cliente
        if ($factura->cliente) {
            $xml->addChild('cliente_nombre', $factura->cliente->nombre ?? '');
            $xml->addChild('cliente_email', $factura->cliente->email ?? '');
        } else {
            $xml->addChild('cliente_nombre', 'Desconocido');
            $xml->addChild('cliente_email', 'no-disponible@ejemplo.com');
        }
    
        // Agregar los detalles de la factura
        $detalles = $xml->addChild('detalles');

        // Asume que tienes un porcentaje de IVA que aplica a todos los productos
        $ivaPorcentaje = 0.13; // 15% de IVA, ajusta según lo necesario
        $totalIVA = 0; // Acumulador para el total de IVA
        $subtotalTotal = 0; // Acumulador para el subtotal total (sin IVA)
        $totalVentaTotal = 0; // Acumulador para el total de venta (con IVA)
        
        foreach ($detallesFactura as $detalle) {
            // Calcula el subtotal por producto (sin IVA)
            $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];
            
            // Calcula el IVA por producto (se calcula sobre el subtotal)
            $iva = $subtotal * $ivaPorcentaje;
        
            // Calcula el total por producto (incluyendo IVA)
            $totalVenta = $subtotal + $iva;
        
            // Agrega el detalle del producto al XML (sin IVA en el total)
            $detalleXml = $detalles->addChild('detalle');
            $detalleXml->addChild('codigo_cabys', $detalle['codigo_cabys']);
            $detalleXml->addChild('cantidad', $detalle['cantidad']);
            $detalleXml->addChild('descripcion', $detalle['descripcion']);
            $detalleXml->addChild('precio_unitario', $detalle['precio_unitario']);
        
        
            // Acumula los totales para el cálculo final
            $totalIVA += $iva;
            $subtotalTotal += $subtotal;
            $totalVentaTotal += $totalVenta;
        }
        
        // Después del bucle, agrega los totales al XML (sin IVA, IVA y total con IVA)
        $totales = $xml->addChild('totales');
        
        // Agrega el subtotal total de todos los productos (sin IVA)
        $totales->addChild('subtotal', number_format($subtotalTotal, 2, '.', ''));
        
        // Agrega el total de IVA de todos los productos
        $totales->addChild('iva', number_format($totalIVA, 2, '.', ''));
        
        // Agrega el total de venta (con IVA)
        $totales->addChild('total', number_format($totalVentaTotal, 2, '.', ''));
        
    
        // Agregar información de la factura
     
        $xml->addChild('tipo', $factura->tipo);
        $xml->addChild('estado', $factura->estado);
        
        return $xml->asXML();  // Devuelve el XML como string
    }
    
     }