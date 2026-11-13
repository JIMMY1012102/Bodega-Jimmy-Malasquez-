# Montaje manual de «Consulta bodega – Malasquez»

## 1. Acceso y evidencia inicial
Abre tu instancia de n8n. Si todavía no tienes una, puedes crearla desde https://n8n.io siguiendo el registro que muestre el servicio.
Captura el editor abierto como 01_n8n_operativo.png.
No compartas API keys en GitHub ni en el chat.

## 2. Credencial
Obtén tu clave desde https://aistudio.google.com y guárdala solo en Credentials de n8n como Google Gemini (PaLM) API.
Elige un modelo disponible para tu cuenta; el nombre de ejemplo del documento puede haber cambiado.
Si usas Groq, crea la credencial correspondiente y conecta Groq Chat Model.
No publiques la clave ni la incluyas en capturas.

## 3. Versión 1: flujo fijo
Crea el workflow con el nombre indicado arriba y añade a mano:
Webhook → Basic LLM Chain → Respond to Webhook.
Conecta Google Gemini Chat Model al puerto Model de Basic LLM Chain.

| Nodo | Campo | Valor |
|---|---|---|
| Webhook | HTTP Method | POST |
| Webhook | Path | bodega |
| Webhook | Respond | Using Respond to Webhook Node |
| Basic LLM Chain | Prompt | Define below |
| Basic LLM Chain | Text | {{ $json.body.pregunta }} |
| Basic LLM Chain | System Message | Eres el asistente de la bodega Doña Rosa de San Vicente de Cañete. Responde en español, en una sola oración, con tono amable. Si no sabes la respuesta, dilo. |
| Respond to Webhook | Respond With | JSON |

Si tu versión del Chain organiza el prompt como mensajes, coloca la instrucción fija como mensaje System y la expresión como mensaje User.
En Response Body del último nodo cambia el campo completo a Expression y escribe:
```javascript
{{ { respuesta: $json.text } }}
```
Comprueba que la salida de Basic LLM Chain contiene text; adapta el campo si tu versión devuelve otro nombre.

Pulsa Listen for test event en Webhook y copia la Test URL exacta.
Desde la terminal de tu laptop Linux:
```bash
read -r -p 'Pega la Test URL de n8n: ' BODEGA_TEST_URL
curl --fail-with-body --max-time 120 -sS -X POST "$BODEGA_TEST_URL" -H 'Content-Type: application/json' --data '{"pregunta":"¿Qué productos tienen descuento?","cliente":"v1-jimmy"}'
```
La Test URL debe estar escuchando antes de cada prueba. No inventar descuentos es el comportamiento buscado.
Guarda 02_flujo_v1.png y 03_respuesta_v1.png antes de modificar el flujo.

## 4. Versión 2: agente
Reemplaza Basic LLM Chain por AI Agent y conecta Webhook → AI Agent → Respond to Webhook.
Conecta el modelo al puerto Model del agente.
Prompt: Define below; Text: {{ $json.body.pregunta }}.
En System Message pega prompt_sistema.txt.

Conecta estos subnodos:
1. Simple Memory al puerto Memory. Session ID en modo Define below: {{ $json.body.cliente ?? 'anonimo' }}. Context Window Length: 5.
2. Calculator al puerto Tool.
3. Code Tool / Custom Code Tool al puerto Tool, nombre productos y descripción: «Devuelve la lista de productos de la bodega con precio y stock; úsala para consultar precios o disponibilidad».

En el campo JavaScript de la herramienta pega el código que ya proporciona la consigna:
```javascript
return JSON.stringify([{nombre:'Arroz Costeño 5 kg',precio:24.5,stock:12},{nombre:'Aceite Primor 1 L',precio:9.8,stock:0},{nombre:'Leche Gloria',precio:4.2,stock:30}]);
```

En Respond to Webhook cambia la expresión completa a:
```javascript
{{ { respuesta: $json.output } }}
```
Comprueba el nombre real del campo en OUTPUT del agente. En la consigna v1 se utiliza text; conservarlo sin comprobar en v2 puede dejar la respuesta vacía.
Si aparece la opción Return Intermediate Steps, actívala para inspeccionar llamadas a herramientas; la ubicación puede variar por versión.

## 5. Pruebas y evidencias
Pulsa Listen for test event antes de cada POST si usas la Test URL.
Utiliza el valor BODEGA_TEST_URL de la terminal anterior:
```bash
curl --fail-with-body --max-time 120 -sS -X POST "$BODEGA_TEST_URL" -H 'Content-Type: application/json' --data '{"pregunta":"¿Cuánto cuestan 3 leches?","cliente":"prueba-leches"}'
curl --fail-with-body --max-time 120 -sS -X POST "$BODEGA_TEST_URL" -H 'Content-Type: application/json' --data '{"pregunta":"¿Tienen aceite?","cliente":"prueba-aceite"}'
curl --fail-with-body --max-time 120 -sS -X POST "$BODEGA_TEST_URL" -H 'Content-Type: application/json' --data '{"pregunta":"¿Qué me recomiendas para el desayuno?","cliente":"prueba-desayuno"}'
```
Ejecuta los comandos uno a uno y reactiva la escucha entre ellos.
Si usas Production URL, activa/publica el workflow según tu versión antes de enviar peticiones.

Pega cada respuesta REAL en 04_respuestas_v2.txt.
Abre la ejecución y comprueba productos y calculadora para las leches (S/ 12.60), y productos para aceite (stock 0).
Para desayuno basta una recomendación general; inspecciona lo que hizo realmente el agente.
Captura las llamadas observadas como 05_agente_decide.png.
Prueba memoria con dos mensajes y el mismo cliente, según 04_respuestas_v2.txt.

## 6. Exportación y MySQL
En el menú del workflow selecciona Download; guarda la exportación real como flujo_v2.json.
Revisa su contenido antes de subirlo: nunca debe contener claves ni contraseñas.
Para la bonificación sigue 06_intento_mysql.md con tu cuenta real. No se ha realizado ese intento desde esta preparación.

## 7. Corpus y lectura
El corpus es una propuesta ficticia con los siete temas obligatorios; valida sus políticas.
En esta tarea v2 usa productos, no consulta automáticamente corpus_bodega.txt. La indexación RAG se realiza la semana siguiente.
Lee ReAct y redacta react.md en tres líneas propias, usando react_guia.md como orientación.
Actualiza prompts.md con tus decisiones.

## Referencias
- Consigna: S11_Tarea_Individual_Agente_n8n.docx, pasos 1–7.
- Respond to Webhook: https://docs.n8n.io/integrations/builtin/core-nodes/n8n-nodes-base.respondtowebhook/
- ReAct: https://arxiv.org/abs/2210.03629

Estado de validación: se verificaron los 20 pares P/R y la correspondencia con los entregables del documento. No se ha validado el montaje en una instancia de n8n.
