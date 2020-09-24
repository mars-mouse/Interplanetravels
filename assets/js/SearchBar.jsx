import React from 'react';

export class SearchBar extends React.Component {
    database = [];

    state = {
        search: '',         // saisie utilisateur
        suggestions: [],    // liste des suggestions
    }

    // Cette méthode n'est exécutée qu'une seule fois : après le premier render du composant SearchBar
    componentDidMount() {
        // Exécution d'une requête HTTP GET vers l'API
        window.fetch('https://localhost:8000/api/travels')
            .then((httpResponse) => {
                // traduit la réponse HTTP brute en JSON
                return httpResponse.json();
            })
            .then((jsonResults) => {
                // on stock les résultats
                this.database = jsonResults;
            });
    }

    onChangeSearch = (event) => {
        this.setState({ search: event.target.value });

        // TODO: 
        // - ajouter la liste des travels liés aux destinations dans les suggestions
        // - ajouter les criteres de date -> ajouter une méthode onChange pour les input date
        //      https://reactjs.org/docs/forms.html#handling-multiple-inputs
        // - gestion des fleches pour naviguer dans les suggestions ?

        // on utilise filter pour ne récupérer que les entrées qui correspondent à ce qu'on a tapé dans l'input
        const filteredList = this.database.filter((destination) => {
            return event.target.value !== '' && destination.name.toLowerCase().includes(event.target.value.toLowerCase());
        });
        // on enregistre dans un state pour provoquer une update
        this.setState({ suggestions: filteredList });
    }

    render() {
        // on crée une liste des suggestions en JSX pour l'affichage
        const listSuggestions = this.state.suggestions.map((value, index) => {
            const linkPath = "./destination/" + value.id;
            return (
                <a key={index} href={linkPath} className="list-group-item">{value.name}</a>
            );
        });


        return (
            <div>
                <form id="searchForm" className="form-inline px-2 py-2 d-flex">
                    <div className="input-group">
                        <input
                            className="form-control pl-4"
                            type="search"
                            placeholder="Destination"
                            aria-label="Search"
                            onChange={this.onChangeSearch}
                        />
                        <div className="input-group-append">
                            <input type="date" className="btn" name="date_departure" id="date_departure" />
                            <input type="date" className="btn" name="date_return" id="date_return" />
                        </div>
                    </div>
                    <button className="btn mx-2" type="submit">Search</button>
                </form>
                <ul id="suggestionList" className="list-group">
                    {listSuggestions}
                </ul>
            </div>
        );
    }
}