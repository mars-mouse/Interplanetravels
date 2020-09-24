/// App.js - entry 'App'

require('../css/style.scss');
const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// JS qui gère la lune en fond flou
require('./index');

// REACT
import React from 'react';
import ReactDOM from 'react-dom';   // vient du dosser node_modules

import { SearchBar } from './SearchBar';    // notre JSX

// si on a la balise react-root dans le document
const reactRootHTML = document.getElementById('react-root');
if (reactRootHTML !== undefined) {
    // on lance la formule magique pour démarrer React
    ReactDOM.render(<SearchBar />, reactRootHTML);
}