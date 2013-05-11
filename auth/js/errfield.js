/* Init SyntaxHighlighter */
SyntaxHighlighter.all();

// Client-side routes
Sammy(function() {
    this.get("reports",function() {
        console.log("hello");
    });

    this.get("home",function() {
        console.log("home");
    });

    this.get('', function() { this.app.runRoute('get', 'home'); });
}).run();