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
    axios(`http://react-site.dev/api/people`).then(response => {
      this.setState({
        names: response.data
      });
    });
  }

  render() {
    return React.createElement(
      'div',
      null,
      React.createElement(
        'h1',
        null,
        'Names'
      ),
      React.createElement(
        'ul',
        null,
        this.state.names.map(name => {
          return React.createElement(
            'li',
            { key: name.nid },
            name.field_name
          );
        })
      )
    );
  }
}

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

ReactDOM.render(React.createElement(FakeList, null), document.getElementById('react-container'));

// ReactDOM.render(
//   <Box size="large" style={{backgroundColor: '#435322', height: '300px', width: '300px'}} />,
//   document.getElementById('react-container')
// );