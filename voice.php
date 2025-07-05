<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Voice to Text</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }

    #output {
      border: 1px solid #ccc;
      padding: 10px;
      min-height: 100px;
      margin-top: 10px;
      white-space: pre-wrap;
    }

    select, button {
      font-size: 16px;
      padding: 5px;
    }

    #start {
      margin-top: 10px;
    }
  </style>
</head>
<body>

  <h1>🎙️ Voice to Text</h1>

  <label for="language">Select Language:</label>
  <select id="language">
    <option value="en-US">English (US)</option>
    <option value="en-GB">English (UK)</option>
    <option value="es-ES">Spanish (Spain)</option>
    <option value="fr-FR">French</option>
    <option value="de-DE">German</option>
    <option value="hi-IN">Hindi</option>
    <option value="ta-IN">Tamil</option> <!-- Tamil added -->
    <option value="ja-JP">Japanese</option>
    <option value="zh-CN">Chinese (Mandarin)</option>
  </select>

  <br><br>
  <button id="start">🎤 Start Listening</button>

  <div id="output" contenteditable="true" placeholder="Your speech will appear here..."></div>

  <script>
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    if (!SpeechRecognition) {
      alert("Your browser does not support Speech Recognition. Try using Google Chrome.");
    } else {
      const recognition = new SpeechRecognition();
      recognition.continuous = true;
      recognition.interimResults = true;

      const outputDiv = document.getElementById("output");
      const startButton = document.getElementById("start");
      const languageSelect = document.getElementById("language");

      let isListening = false;

      languageSelect.addEventListener("change", () => {
        recognition.lang = languageSelect.value;
      });

      startButton.addEventListener("click", () => {
        if (isListening) {
          recognition.stop();
          startButton.textContent = "🎤 Start Listening";
        } else {
          recognition.lang = languageSelect.value;
          recognition.start();
          startButton.textContent = "🛑 Stop Listening";
        }
        isListening = !isListening;
      });

      recognition.onresult = (event) => {
        let transcript = "";
        for (let i = event.resultIndex; i < event.results.length; i++) {
          transcript += event.results[i][0].transcript;
        }
        outputDiv.textContent = transcript;
      };

      recognition.onerror = (event) => {
        console.error("Speech recognition error:", event.error);
        alert("Error occurred: " + event.error);
      };

      recognition.onend = () => {
        if (isListening) {
          recognition.start();
        }
      };
    }
  </script>
</body>
</html>
