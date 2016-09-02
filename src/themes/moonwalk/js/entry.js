/**
 * @file
 * Entry point for ReactJS and custom module builds.
 *
 */
// import React, { Component, PropTypes } from 'react';
// import React from '../node_modules/react';
var React = require('react');
var ReactDOM = require('react-dom');
var axios = require('axios');


class FakeList extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      names: []
    };
  }

  componentWillMount() {
    axios(`http://react-site.dev/api/people`)
      .then((response) => {
        this.setState({
          names: response.data
        });
      });
  }

  render() {
    return(
      <div>
      <h1>Names</h1>
        <ul>
          {this.state.names.map((name) => {
            return <li key={name.nid}>{name.field_name}</li>;
          })}
        </ul>

      </div>
    );
  }
}

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
  <FakeList/>,
  document.getElementById('react-container')
);


// ReactDOM.render(
//   <Box size="large" style={{backgroundColor: '#435322', height: '300px', width: '300px'}} />,
//   document.getElementById('react-container')
// );
