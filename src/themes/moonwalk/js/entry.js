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


let SayHello = (props) => {
  return (
    <div>Hello {props.firstName} {props.lastName}</div>
  );
};

let Box = (props) => {
  return (
    <div>
      <SayHello firstName="Jason" lastName="Cote"/>
      <div className={`Box Box--${props.size}`} style={props.style}>
        {props.children}
      </div>
    </div>
  );
};




ReactDOM.render(
  <Box size="large" style={{backgroundColor: '#435322', height: '300px', width: '300px'}} />,
  document.getElementById('react-container')
);
