var inputWin = document.getElementById('inputWin');
var mainWin = document.getElementById("mainWin");

function oreMap(){
    var req = new XMLHttpRequest();
    var url = "index.php?p=map.readMap"
    req.open("GET", url);
    req.addEventListener("load", function () {

        result = JSON.parse(req.responseText);
        map = result[0];
        mapSettings = result[1];
        
        mapSizeX = mapSettings["gridSizeX"];
        mapSizeY = mapSettings["gridSizeY"];
        var wrapper = document.createElement("div");
        wrapper.id = "mapWrapper";
        wrapper.style = "line-height: 0.6em;";
        separatorText1 = "_";
        separatorText2 = "‾";
        separatorText1 = separatorText1.repeat(mapSizeY);
        separatorText2 = separatorText2.repeat(mapSizeY);
        separator1 = document.createElement("span");
        separator2 = document.createElement("span");
        separator1.innerHTML = separatorText1;
        separator2.innerHTML = separatorText2;
        separator1.style = "line-height: 1.3em";
        separator2.style = "line-height: 1.4em";
        
        wrapper.appendChild(document.createElement("br"));
        wrapper.appendChild(separator1);
        wrapper.appendChild(document.createElement("br"));

        for (let x = 0; x < mapSizeX; x++) {
            for (let y = 0; y < mapSizeY; y++) {
                value = 0;
                valueType = "";
                valueR = 0;
                valueG = 0;
                valueB = 0;
                stringX = String(x);
                stringY = String(y);
                if (typeof map[stringX] !== 'undefined'){
                    if (typeof map[stringX][stringY] !== 'undefined'){
                        valueType = map[stringX][stringY]["type"];
                        value = map[stringX][stringY]["value"];
                        if (valueType == "iron"){
                            valueR = Math.round(value*94);
                            valueG = Math.round(value*164);
                            valueB = Math.round(value*221);
                        } else if (valueType == "copper"){
                            valueR = Math.round(value*242);
                            valueG = Math.round(value*118);
                            valueB = Math.round(value*29);
                        }
                    }
                }
                var span = document.createElement("span");
                span.style.color = "rgb("+valueR+","+valueG+","+valueB+")";
                span.id = "x"+x+"y"+y;
                if (valueType != ""){
                    span.className = valueType + ": " + Math.round(value*1000);
                };
                span.innerHTML = "█";
                wrapper.appendChild(span);
            }
            wrapper.appendChild(document.createElement("br"));
        }
        
        mapSubText = document.createElement("span");
        mapSubText.innerHTML = "Area successfully scaned. Press arrow keys to navigate and \"Ctrl + C\" to exit.";
        mapSubText.style = "line-height: 1.3em";
        locIndicatorX = document.createElement("span");
        locIndicatorY = document.createElement("span");
        locIndicatorDetails = document.createElement("span");
        locIndicatorX.id = "locIndicatorX";
        locIndicatorY.id = "locIndicatorY";
        locIndicatorDetails.id = "locIndicatorDetails";
        locIndicatorX.innerHTML = "0";
        locIndicatorY.innerHTML = "0";
        locIndicator = document.createElement("span");
        locIndicator.style = "line-height: 1.3em";
        locIndicator.innerHTML = "Loc X:";
        locIndicator.appendChild(locIndicatorX);
        locIndicator.innerHTML += " Y:";
        locIndicator.appendChild(locIndicatorY);
        locIndicator.innerHTML += " ";
        locIndicator.appendChild(locIndicatorDetails);
        wrapper.appendChild(separator2);
        wrapper.appendChild(document.createElement("br"));
        wrapper.appendChild(mapSubText);
        wrapper.appendChild(document.createElement("br"));
        wrapper.appendChild(locIndicator);
        mainWin.appendChild(wrapper);
        mainWin.scrollTop = mainWin.scrollHeight;
        mapMode(mapSizeX, mapSizeY);
    });
    req.send(null);
    
    
}

function mapMode(maxX, maxY){
    disableInputField()
    x = Math.round(maxX/2);
    y = Math.round(maxY/2);

    document.getElementById("locIndicatorX").innerHTML = x;
    document.getElementById("locIndicatorY").innerHTML = y;

    oldCursor = document.querySelector("#x"+x+"y"+y);
    oldCursorStyle = oldCursor.style.color;
    oldCursor.style = "color: white;";

    console.log(oldCursor);

    
    var _listenerbis = function (e) {
        var key = e.which || e.keyCode;
        if (key === 17) { // 17 is ctrl
            ctrl = false;
        }
    }
    
    
    document.addEventListener('keyup', _listenerbis, true);
    document.addEventListener('keydown', function _listener(e) {
        var key = e.which || e.keyCode;
        if (key === 39) { // 39 is right arrow
            if (y <= (maxY-2)){
                newPos = mapModeMove(x, y, "right", oldCursorStyle);
                x = newPos[0];
                y = newPos[1];
                oldCursorStyle = newPos[2];
            }
        } else if (key === 37) { // 39 is left arrow
            if (y >= 1){
                newPos = mapModeMove(x, y, "left", oldCursorStyle);
                x = newPos[0];
                y = newPos[1];
                oldCursorStyle = newPos[2];
            }
        } else if (key === 38) { // 38 is up arrow
            if (x >= 1){
                newPos = mapModeMove(x, y, "up", oldCursorStyle);
                x = newPos[0];
                y = newPos[1];
                oldCursorStyle = newPos[2];
            }
        } else if (key === 40) { // 40 is down arrow
            if (x <= (maxX-2)){
                newPos = mapModeMove(x, y, "down", oldCursorStyle);
                x = newPos[0];
                y = newPos[1];
                oldCursorStyle = newPos[2];
            }
        } else if (key === 17) { // 17 is ctrl
            ctrl = true;            
        } else if (ctrl && key === 67) { // 67 is C
            cursor = document.querySelector("#x"+x+"y"+y);
            cursor.style = oldCursorStyle;
            document.removeEventListener('keydown',  _listener, true);
            document.removeEventListener('keyup', _listenerbis, true);
            mainWin.removeChild(document.getElementById("mapWrapper"));            
            addLine("");
            enableInputField();
            inputWin.disabled = false;
            inputWin.focus();

        } 
    }, true);

}



function mapModeMove(x, y, direction, oldCursorStyle){
    oldCursor = document.querySelector("#x"+x+"y"+y);
    if(direction == "right"){
        y++;
    } else if (direction == "left"){
        y--;
    } else if (direction == "up"){
        x--;
    } else if (direction == "down"){
        x++;
    }
    cursor = document.querySelector("#x"+x+"y"+y);
    oldCursor.style = "color: "+oldCursorStyle+";";
    oldCursor = document.querySelector("#x"+x+"y"+y);
    oldCursorStyle = oldCursor.style.color;
    cursor.style = "color: white;";
    document.getElementById("locIndicatorX").innerHTML = x;
    document.getElementById("locIndicatorY").innerHTML = y;
    document.getElementById("locIndicatorDetails").innerHTML = cursor.className;
    return [x, y, oldCursorStyle];
}

function mapgen(){
    var req = new XMLHttpRequest();
    var url = "index.php?p=map.generateOreMap"
    req.open("GET", url);
    req.addEventListener("load", function () {
        //var mapSettings = JSON.parse(req.responseText);
        //console.log(req.responseText);
    });
    req.send(null);
    addLine("Map generated successfully !")
};
