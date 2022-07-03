package main

import (
	"fmt"
	"log"
	"net/http"

	"github.com/gorilla/mux"
)

func handleRequest(w http.ResponseWriter, r *http.Request) {
	to := r.URL.Query().Get("to")
	amount := r.URL.Query().Get("amount")
	causal := r.URL.Query().Get("causal")
	fmt.Fprintf(w, "To: "+to+" amount: "+amount+" causal: "+causal)
}

func main() {
	router := mux.NewRouter().StrictSlash(true)
	router.HandleFunc("/", handleRequest)
	log.Fatal(http.ListenAndServe(":8080", router))
}
