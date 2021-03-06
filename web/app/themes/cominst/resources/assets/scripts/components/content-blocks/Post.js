import React, { Component } from 'react';
import PropTypes from 'prop-types';

import { Link } from 'react-router-dom';

import scrollToComponent from 'react-scroll-to-component';

class Post extends Component {

  constructor (props) {
    super(props);

    this.state = {
      active: props.active || false,
      contentStyles: {
        transition: 'height 0.4s ease-out, opacity 0.4s ease-out',
        overflow: 'hidden',
      },
    }

    this._toggleButtonClickHandler = this._toggleButtonClickHandler.bind(this);
    this._contentContainerTransitionEndHandler = this._contentContainerTransitionEndHandler.bind(this);

  }

  componentWillReceiveProps (nextProps) {
    if(this.props.active !== nextProps.active &&
      nextProps.active) {
        this._toggleContent();
    }
  }

  componentDidMount () {
    this.originalContentHeight = this._contentBoxRef.clientHeight;
    this.setState( () => (
      {
        contentStyles: {
          ...this.state.contentStyles,
          height: this.originalContentHeight,
        },
      }
    ));

    if(this.state.active) {
      scrollToComponent(
      this,
      {
        offset: - this.props.siteHeaderHeight - 50, // 50 is the content-container paddingTop - should be dynamic
        align: 'top',
        duration: 300,
      }
    );
    }
  }

  _toggleActive ( callback = () => {} ) {
    this.setState( (state) => (
        {
          ...state,
          active: ! state.active,
        }
      ),
      callback
    );
  }

  _contentContainerTransitionEndHandler () {
    this._toggleActive();
    this.props.history.push(this.props.postsListPath);
    this.setState((state) => ({
      contentStyles: {
        ...state.contentStyles,
        opacity: 1,
      },
    }));
    this._contentContainerRef.removeEventListener(
      'transitionend',
      this._contentContainerTransitionEndHandler,
      true
    );
  }

  _toggleContent() {

    if(this.state.active) {
      this.setState((state) => ({
        contentStyles: {
          ...state.contentStyles,
          height: this.originalContentHeight,
          opacity: 0.1,
        },
      }));
      this._contentContainerRef.addEventListener(
        'transitionend',
        this._contentContainerTransitionEndHandler,
        true
      )
    }
    else {
      this._toggleActive(
        () => (
          this.setState((state) => ({
            contentStyles: {
              ...state.contentStyles,
              height: this._contentBoxRef.scrollHeight,
            },
          }))
        )
      );
    }

    scrollToComponent(
      this,
      {
        offset: - this.props.siteHeaderHeight - 50, // 50 is the content-container paddingTop - should be dynamic
        align: 'top',
        duration: 300,
      }
    );
  }

  _toggleButtonClickHandler (event) {
    event.preventDefault();
    this._toggleContent();
  }

  _renderMetaData () {
    const {
      data,
    } = this.props;

    const categories_names =
      data.categories_names.map(
        (name, index) => (
          <span className="category" key={index} dangerouslySetInnerHTML={ {__html: name } } />
        )
      );

    const medias_names = data.medias_names.length ?
      data.medias_names.map(
        (name, index) => {
          const separator = data.medias_names.length > 0 && index !== data.medias_names.length - 1 ? ', ' : '';
          return (
            <span className="meta-data-media" key={index}> <span dangerouslySetInnerHTML={ {__html: name } } />{ separator }</span>
          );
        }
      )
      : '';

    if(medias_names) {
      return (
        <div className="meta-data meta-data-ci-medias">
          { medias_names }
          <span className="meta-data-date"> <span dangerouslySetInnerHTML={ {__html: data.formatted_published_date } } /> </span>
        </div>
      );
    }
    else {
      return (
        <div className="meta-data">
          { categories_names }
          <span className="meta-data-date"> <span dangerouslySetInnerHTML={ {__html: data.formatted_published_date } } /> </span>
        </div>
      );
    }
  }

  render () {
    const {
      data,
      data: { content: { rendered: content }},
      data: { excerpt: { rendered: excerpt }},

    } = this.props;
    const activeContent = this.state.active ? content : excerpt.replace(/<a.*?>.*?<\/a>/g, '');
    return (
      <article key={ data.id } className="child-content-container post">
        <div className="header">

          { this._renderMetaData() }

          <h3 className="title" dangerouslySetInnerHTML={ {__html: data.title.rendered } } />
        </div>
        <div className="content" style={this.state.contentStyles} ref={ (element) => this._contentContainerRef = element }>
          <div ref={ (element) => this._contentBoxRef = element } dangerouslySetInnerHTML={ {__html: activeContent } } />
        </div>
        <div className="has-more-content actions">
          {
            ! this.state.active ?
              <Link to={data.path}>
                <span className="collapsed">
                  <svg className="expand-button" width="29px" height="29px" viewBox="0 0 29 29" version="1.1" xmlns="http://www.w3.org/2000/svg">
                      <defs></defs>
                      <g id="expand-button-container" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd" strokeLinecap="square">
                          <g transform="translate(1.000000, 1.000000)" stroke="#018EC0" strokeWidth="2">
                              <path d="M0,13 L27,13"></path>
                              <path d="M14,27 L14,0"></path>
                          </g>
                      </g>
                  </svg>
                </span>
              </Link> :
              <a href="#" onClick={this._toggleButtonClickHandler}>
                <span className="collapsed">
                  <svg className="expand-button" width="29px" height="29px" viewBox="0 0 29 29" version="1.1" xmlns="http://www.w3.org/2000/svg">
                      <defs></defs>
                      <g id="expand-button-container" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd" strokeLinecap="square">
                          <g transform="translate(1.000000, 1.000000)" stroke="#018EC0" strokeWidth="2">
                              <path d="M0,13 L27,13"></path>
                          </g>
                      </g>
                  </svg>
              </span>
              </a>
          }
        </div>
      </article>
    )
  }
}

Post.propTypes = {
  data: PropTypes.object,
  parent: PropTypes.object,
  active: PropTypes.bool,
  postsListPath: PropTypes.string,
  history: PropTypes.object,
  siteHeaderHeight: PropTypes.number,
}

export default Post;
