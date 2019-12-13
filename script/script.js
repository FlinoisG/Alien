var inputWin = document.getElementById('inputWin');
var mainWin = document.getElementById("mainWin");




// execute function
inputWin.addEventListener('keypress', executeListener, true);

function executeListener(e) {
    var key = e.which || e.keyCode;
    if (key === 13) { // 13 is enter
        var inputText = inputWin.value;
        if (inputText.includes(" ")){
            var index = inputText.indexOf(" ");
            var command = inputText.substr(0, index);
            var args = inputText.substr(index+1);
            var args = args.split(" ");
            execute(command, args);
        } else {
            execute(inputText);
        }
        if (inputText != "") {
            commandHistory.push(inputText);
        }
        commandHistoryCounter = commandHistory.length-1;
    }
}

// command history
var commandHistory = [];
var commandHistoryCounter = 0;

inputWin.addEventListener('keydown', historyListener, true);

function historyListener(e) {
    var key = e.which || e.keyCode;
    if (key === 38) { // 38 is up arrow
        if(commandHistory.length > 0 && commandHistoryCounter <= commandHistory.length){
            inputWin.value = commandHistory[commandHistoryCounter];
            if (commandHistoryCounter > 0){
                commandHistoryCounter--;
            }
        }
    }
};

function addLine(line) {
    spanLine = document.createElement("span");
    spanLine.innerHTML = line;
    mainWin.appendChild(spanLine);
    mainWin.innerHTML += '<br/>';
    inputWin.value = "";
    mainWin.scrollTop = mainWin.scrollHeight;
}

function disableInputField(){
    inputWin.innerHTML = "";
    inputWin.disabled = true;
}

function enableInputField(){
    inputWin.disabled = false;
}

function isEven(value) {
	if (value%2 == 0)
		return true;
	else
		return false;
}


