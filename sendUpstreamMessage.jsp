<%@ page import="java.io.*, java.util.*,org.json.simple.*,org.json.simple.parser.*"%>
<% 
    String action="";
    JSONParser jsonParser;
    JSONObject jsonObject;
    JSONArray id = new JSONArray();
    
    try{
    BufferedReader br = new BufferedReader(new InputStreamReader(request.getInputStream()));
    String json = br.readLine();

    jsonParser = new JSONParser();
    jsonObject = (JSONObject)jsonParser.parse(json);
    id =(JSONArray) jsonObject.get("ids");
    action = (String)jsonObject.get("action");
    }
    catch(ParseException p){
        out.println("Parse Exception");
    }

    
    
    out.println((String)id.get(0));
%>