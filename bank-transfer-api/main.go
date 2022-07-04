package main

import (
	"fmt"
	"log"
	"net/http"

	"github.com/gorilla/mux"
)

func handleRequest(w http.ResponseWriter, r *http.Request) {
	from := r.URL.Query().Get("from")
	recipient := r.URL.Query().Get("recipient")
	amount := r.URL.Query().Get("amount")
	// causal := r.URL.Query().Get("causal")
	fmt.Fprintf(w, "Transfer of "+amount+"$ from "+from+" to "+recipient+" complete")
	fmt.Println("From: " + from + "\n Recipient: " + recipient + "\n amount: " + amount)
}

func main() {
	router := mux.NewRouter().StrictSlash(true)
	router.HandleFunc("/", handleRequest)
	log.Fatal(http.ListenAndServe(":8081", router))
}
