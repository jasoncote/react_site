/**
 * @file
 * Entry point for ReactJS and custom module builds.
 *
 */
// import axios from 'axios';
// import React, { Component, PropTypes } from 'react';
// import React from '../node_modules/react';
var React = require('react');
var ReactDOM = require('react-dom');

let SayHello = props => {
  return React.createElement(
    'div',
    null,
    'Hello ',
    props.firstName,
    ' ',
    props.lastName
  );
};

let Box = props => {
  return React.createElement(
    'div',
    null,
    React.createElement(SayHello, { firstName: 'Jason', lastName: 'Cote' }),
    React.createElement(
      'div',
      { className: `Box Box--${ props.size }`, style: props.style },
      props.children
    )
  );
};

ReactDOM.render(React.createElement(Box, { size: 'large', style: { backgroundColor: '#435322', height: '300px', width: '300px' } }), document.getElementById('react-container'));