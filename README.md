
------------------------------------------------------------------------

\
**Penetration Test Report**

------------------------------------------------------------------------

# About the Vulnerable Application

The application tries to replicate an online banking site where the
client can make money transfers to other people (with a simple form).
Moreover the client can view the list of transactions made.

The code of the entire application is on
[[Github](https://github.com/guglielmobartelloni/penetration-testing-project)]{style="color: dkblue"}
at:

<https://github.com/guglielmobartelloni/penetration-testing-project>.

## Design Choices

For simplicity reasons there is no authentication involved. The
application supposes that the user is logged in.

When a transaction is created the PHP server creates a row into the
database and makes a request to an external API written in Go, this API
is responsible of making the transfer and it returns the outcome:

![image](images/appFlow/MoneyTransferFlow.png){width="\\textwidth"}

### PHP Appliation

There are three main pages for the application:

-   index.php - the main page were the user can make transfers.

-   createTransfer.php - the page that make the request to the go api to
    make the transfer and inserts the transaction row to the database.

-   bankTransfers.php - the page were the client can view the list of
    the transactions.

-   createSensitiveData.php - this page is to generate sensitive data to
    test vulnerabilities.

The basic interface is the following:

To connect the PHP application to the database we used the MySQLi
extension with a connection pattern similar to this:

``` {.php language="php" frame="single" showstringspaces="false"}
$conn = new mysqli($server, $username, $password, $db_name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
```

To make queries we used the following code:

``` {.php language="php" frame="single" showstringspaces="false"}
$sql = "SELECT * FROM bank_transfers;";
$result = $conn->query($sql);
```

In the page $createTransaction.php$ the application takes the parameters
coming from a POST request with the following code:

``` {.php language="php" frame="single" showstringspaces="false"}
//Take parameters from post
$recipient = $_POST['recipient'];
$amount = $_POST['amount'];
$causal = $_POST['causal'];
```

Notice that there isn't any sanitization of the parameters therefore
this is a security hole that we choose to leave to demonstrate an unsafe
code.

Furthermore this parameters are used in an insert query without any
checks:

``` {.php language="php" frame="single" showstringspaces="false"}
$sql = "INSERT INTO bank_transfers(sender, receiver, amount, causal) 
VALUES(
'$from',
'$recipient', 
$amount,
'$causal');";
```

Even the request to the Go API is unsafe for the same reasons:

``` {.php language="php" frame="single" showstringspaces="false"}
$request_url =
"http://backend:8081?
recipient=$recipient&from=$from&amount=$amount&causal=$causal";
```

### Go API

This is a simple dummy API that on request returns the transfer the
string \"Transfer of amount from sender to reciever complete\". It uses
the port 8081 and it takes four parameters the sender, the reciever, the
amount and the causal. The handle method code is the following:

``` {.php language="php" frame="single" showstringspaces="false"}
from := r.URL.Query().Get("from")
recipient := r.URL.Query().Get("recipient")
amount := r.URL.Query().Get("amount")
causal := r.URL.Query().Get("causal")
fmt.Fprintf(w, 
    "Transfer of "+amount+"$ from "+from+" to "+recipient+" complete")
```

Everything runs with Docker containers in particular the PHP server
(frontend), the Go API (backend) and the database (database) are
connected together a network called $main-net$. Here is the
$docker-compose.yml$:

    version: "3.9"

    services:
      frontend:
        build: .
        ports: 
          - "80:80"
        depends_on:
          - database
        networks:
          - main-net
      database:
        image: arm64v8/mysql
        restart: always
        environment:
          MYSQL_DATABASE: 'db'
          MYSQL_USER: 'user'
          MYSQL_PASSWORD: 'password'
          MYSQL_ROOT_PASSWORD: 'password'
        ports:
          - '3306:3306'
        expose:
          - '3306'
        networks:
          - main-net
      backend:
        build: bank-transfer-api
        ports: 
          - "8081:8081"
        depends_on:
          - frontend
        networks:
          - main-net   
    networks:
      main-net:

## Used Technologies

-   PHP for the website itself.

-   GoLang for the money transfer api.

-   MySQL for the database.

-   Docker for deploy.

-   Github for source control.

# Report Overview

## History Logs

Version: 1.0

Date: 2023-08-17

First version of the report modified by Guglielmo Bartelloni, Francesco
Marchini and Leonardo Baragli.

## Executive Summary

Analysing were discovered many vulnerabilities that could be exploited
from a malicious user. There are three main vulnerabilities in the
application:

1.  Cross Site Scripting (Medium)

2.  SQL Injection (High)

3.  HTTP Parameter Pollution (Low)

The first vulnerability comes from the fact that there is no control on
the user input so an attacker can use a $causal$ like
$<script>alert('hacked')</script>$ and perform and execute any type of
script on the website. This XSS attack is stored in the database so when
the user goes to $bankTransactions.php$ the script injected will run.

The second vulnerability comes from the same form, and an attacker can
inject some type of SQL code to, for example, create an unauthorized row
in the database so this is a dangerous vulnerability that needs to be
addressed as soon as possible. An attacker can't however execute delete
or update commands but only insert or select rows from the database.

The third vulnerability comes from the way the Go API parses parameters:
if there are multiple parameters with the same name GoLang will take the
first occurence, knowing this, an attacker can exploit the Go API by
sending a the recipient input like $recipient\&from=victim$ so it can
transfer money from any victim he wants and send the money wherever he
wants, this is very dangerous for a bank.

The good news is that this vulnerabilities can be mitigated by simply
checking the parameters sent from the user by not allowing some type of
characters and by encoding the request to the API. This are very simple
modifications that needs to be added to the code.

## Scope of Engagement

The application was tested upon the full scope available without any
limitations moreover, the source code of the application was given to
us.

# Observations

This section serves as a high level overview of the security of
application. The details of all vulnerabilities can be found in Section
[4](#sec:tech){reference-type="ref" reference="sec:tech"}.

## Summary of Vulnerabilities

The following is an overview of discovered vulnerabilities, with the
recommendation which should be followed:

-   SQL Injection [4.1.1](#sec:sqli){reference-type="ref"
    reference="sec:sqli"}: is recommended to use safe method in the
    source code and check the user input;

-   XSS Cross Site Scripting [4.2.1](#sec:xss){reference-type="ref"
    reference="sec:xss"}: like in SQL Injection is important to validate
    the user input to prevent this kind of attack;

-   HTTP Parameter Pollution
    [4.3.1](#sec:HTTP_Parameter_Pollution){reference-type="ref"
    reference="sec:HTTP_Parameter_Pollution"}: the last vulnerabilities
    found is also about a lack of control over inputs, we recommend to
    encode the GET parameters sent to the API.

# Technical Findings {#sec:tech}

This table shows the total number of vulnerabilities found during the
penetration test engagement. The vulnerabilities are categorized based
on the risk level. The risk levels were calculated using the Common
Vulnerability Scoring System (CVSS) [@cvssdocs].

::: center
**Risk Level and Total Number of Discovered Vulnerabilities**
:::

  Severity              Low (0.1-3.9)   Moderate (4.0-6.9)   High (7.0-8.9)   Critical (9.0-10.0)
  --------------------- --------------- -------------------- ---------------- ---------------------
  Vulnerability Count   1               1                    1                0

The following table breaks down the discovered vulnerabilities by
overall risk score, impact, and exploitability. The scores were
calculated using NIST's CVSS v3.1 calculator [@nvdcvss].

::: center
**Summary of Vulnerabilities by Base Score**
:::

  **Risk Summary**            **Overall Risk Score**  **Impact**    **Exploitability**
  -------------------------- ------------------------ ------------ --------------------
  SQL Injection                        8.9            6                    2.3
  XSS Stored                           4.1            2.5                  1.5
  HTTP Parameter Pollution             3.5            1.4                  2.1

## High Risk

### SQL Injection {#sec:sqli}

**Threat Level**:

**Description**:

An attacker can manipulate the SQL statements that is being inserted
into the database in the transfer form.

Sending malicious data through the transfers form leads to the access
of:

-   Customer Accounts and passwords.

-   Customer Card Numbers and personal information.

**Potential Business Impact**:

This vulnerability can severely impact the Confidentiality of the data
in the system.

The attacker can access all the user profiles and get system control.
All the data stored in the database can be read by the attacker so
everything that is in plain text is readable.

It is recommended to fix this problem as soon as possible.

**Exploitation Details**:

All customer information could be obtained without modifying the
database schema or contents. This can be done by using an SQL payload in
the transaction form like:

``` {.php language="php" frame="single" showstringspaces="false"}
' or extractvalue(1, concat('~',
            (select password from sensitive_data limit 1))) or '
```

This payload uses the function $extractvalue$ that in this case returns
an error where the content of the error message is the result of the
select statement. An attacker can use any select query he wants.

Using the previous payload the resulting query is the following:

``` {.php language="php" frame="single" showstringspaces="false"}
INSERT INTO bank_transfers(sender, receiver, amount, causal) 
VALUES( 'Elliot',
'MrRobot', 
123456, 
'' or extractvalue(1,concat('~',
            (select password from sensitive_data limit 1))) or '');
```

And it returns the following error:

**Recommended Remediation**:

To prevent this kind of attacks we recommend to use a prepared statement
to execute the SQL query, like:

``` {.php language="php" frame="single" showstringspaces="false"}
$stmt = $pdo->prepare('INSERT INTO banck_tranfers
sender, 
reciever, 
amount, 
causal) 
VALUES(:sender,:receiver, :amount, :causal)';
$stmt->execute([ 
'sender' => $from, 
'receiver' => $recipient, 
amount => $amount, 
'causal' => $causal]);
```

This prevents any SQL injection attack because the input are filtered
and any malicious payload can't be used.

**References**:

<https://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php>

<https://www.exploit-db.com/docs/33253>

## Moderate Risk

### XSS {#sec:xss}

**Threat Level**:

**Description**:

This vulnerability is one of the most popular attacks that an hacker can
perform. There is an high change that it will be exploited, the risk is
that any client side script can be executed so this opens any kind of
user related attack.

In particular this is a Stored XSS type attack because the XSS payload
is stored in the database.

Images [\[fig:xss1\]](#fig:xss1){reference-type="ref"
reference="fig:xss1"} and [\[fig:xss2\]](#fig:xss2){reference-type="ref"
reference="fig:xss2"} illustrate an alert function being executed on
client side:

**Potential Business Impact**:

As stated before an attacker can execute any type of malicious
Javascript payload on the client side, this can lead to reading personal
user's data, capture bank credentials, inject trojans and rootkits on
the victim's computer and many more.

For the user this is a very destructive attack and it compromises, in
some cases, the functionality of the application.

More importantly can make a user to lose money.

**Exploitation Details**:

Using the money transfer form in the $index.php$ page, an attacker can
inject a script tag in any of the fields, for example
$<script>alert("this is an attack")</script>$.

Based on that any script can be inserted on the place of the alert
function for example a redirect to a malicious site like so
$window.location.replace("https://attacker.com/steal-money");$.

**Recommended Remediation**:

The simplest way to deal with an attack like this is to sanitize the
form inputs on the server side with a simple php function like the
following example:

``` {.php language="php" frame="single" showstringspaces="false"}
$recipient = htmlspecialchars($recipient);
$amount = htmlspecialchars($amount);
$causal = htmlspecialchars($causal);
```

This lets the browser interpret the user input as text only without
validating any html tag. Another potential way to mitigate this attack
is to allow only certain type of characters for example forbidding
angled brackets. We suggest to use the first solution because it
considers edge cases.

**References**:

<https://it.wikipedia.org/wiki/Cross-site_scripting>

<https://www.php.net/manual/en/function.htmlspecialchars.php>

## Low Risk

### HTTP Parameter pollution {#sec:HTTP_Parameter_Pollution}

**Threat Level**:

**Description**:

This vulnerability consists in performing a money transaction using a
payload in the parameter $recipient$. The string allows the attacker to
build the HTTP request that is sent to the server overwriting the input
parameter.

**Potential Business Impact**:

This vulnerability allows an user (the attacker) to steal how much money
he wants from an other user (the victim). In this case, a bank system,
this kind of attack is very dangerous because all bank accounts aren't
safe, so the money of the user isn't protected.

**Exploitation Details**:

The normal HTTP request generated from the $createTransfer.php$ is

    http://backend:8081?recipient=$recipient&from=$from&amount=$amount&causal=$causal

where $recipient$ is the recipient of the transaction, $from$ is the
sender, $amount$ the amount of money we want to send and $causal$ the
causal of the transaction. By default $from$ is initialized with logged
user, in our case $Elliot$, but if the attacker inserts in the
$recipient$ field a payload like $Elliot\&from=<anotherUser>$ he can
replace the $from$ field and effectively redirecting the money transfer
from $anotherUser$.

This is possible because the API is written in $GoLang$ and if there is
more than one parameter with the same name will be taken the first
occurence. In the case of the attack, the API request will be like so:

    http://backend:8081
    ?recipient=Elliot&from=MrRobot&from=Elliot&amount=123456&causal=Trying_to_steal_money

We can see that there are two $from$ parameters, the first the
attacker's payload $MrRobot$, the second the one generated by the
application $Elliot$. As said the Golang API will take the first
occurrence therefore $MrRobot$ is used.

**Recommended Remediation**:

To prevent this vulnerability we recommend to use the $urlencode$
function in $php$ to encode the HTTP parameters.

``` {.php language="php" frame="single" showstringspaces="false"}
$recipient = $_POST['recipient'];
    $amount = $_POST['amount'];
    $causal = $_POST['causal'];
    
    $recipient = urlencode($recipient);
    $amount = urlencode($amount);
    $causal = urlencode($causal);
```

This will make the attacker's payload useless because it will be
interpreted as simple text and not as another parameter.

**References**:

<https://www.youtube.com/watch?v=QVZBl8yxVX0>

<https://securityintelligence.com/posts/how-to-prevent-http-parameter-pollution>

## Informational

### Insufficient controls {#sec:insufficient-firewalls}

**Description**

During the engagement the service resulted unsafe, mainly for some
missing input checks and for using unsafe methods in the source code.

**Potential Business Impact**

Implementing the suggested modifications the system could be safe from
potential attacks by any malicious user and all the users from privacy
violation and thefts.

**Recommended Remediation**

The suggestion is to use some verified and safe methods that do a string
parsing of the inputs (see the 4.1, 4.2 and 4.3 section for details).
For example to prevent critical attacks using
$HTTP\ Parameter\ Pollution$ and $SQL\ injection$ a remediation could be
to insert a routine that performs an input check in the backend code for
the money transactions, this way the attacker can't insert any malicious
string.

**References**:

[https://www.simplilearn.com/tutorials/php-tutorial\\\\/php-form-validation](https://www.simplilearn.com/tutorials/php-tutorial\\/php-form-validation){.uri}

# Conclusion

The system is affected by many vulnerabilities that make it an extremely
unsafe application both for managers and users; a malicious user can
perform, as already mentioned, many type of attacks with many targets,
like to steal confidential data or money. The system would not be
excessively damaged from this kinds of attacks, but in term of
responsibilities it could be very dangerous. Radical modification are
strongly recommended.

# Tools

  **Name**                 **Description**                                                   **Link**
  ------------------------ ----------------------------------------------------------------- -----------------------------------------
  Portswigger Burp Suite   Web traffic analysis tool                                         <https://portswigger.net/burp>
  Nikto                    Web servers scanner                                               <https://www.kali.org/tools/nikto>
  Dirbuster                Directory brute force tool                                        <https://www.kali.org/tools/dirbuster/>
  Nmap                     Open source utility for network discovery and security auditing   <https://nmap.org/>
