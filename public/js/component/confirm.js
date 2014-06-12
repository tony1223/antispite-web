/** @jsx React.DOM */

var converter = new Showdown.converter();

var Comment = React.createClass({
  render: function() {
    //var rawMarkup = converter.makeHtml(this.props.children.toString());
    var comment = this.props.data;
    return (
        <tbody>
          <tr className="comment-row-head">
            <td>key</td>
            <td>回報時間</td>     
            <td>類型</td>
            <td>留言者</td>
            <td>留言時間</td>
            <td>回報數</td>
          </tr>
          <tr className="comment-row-head">
            <td>1</td>
            <td>2</td>     
            <td>3</td>
            <td>4</td>
            <td>5</td>
            <td>6</td>
          </tr>          
        </tbody>
    );
  }
});

var CommentBox = React.createClass({
  loadCommentsFromServer: function() {
    // $.ajax({
    //   url: this.props.url,
    //   success: function(data) {
    //     this.setState({data: data});
    //   }.bind(this)
    // });
  },
  handleCommentSubmit: function(comment) {
    // var comments = this.state.data;
    // comments.push(comment);
    // this.setState({data: comments});
    // $.ajax({
    //   url: this.props.url,
    //   type: 'POST',
    //   data: comment,
    //   success: function(data) {
    //     this.setState({data: data});
    //   }.bind(this)
    // });
  },
  getInitialState: function() {
    return {data: [{"_id":"ugccmt-comment_1400062658251-39c8c233-1fcb-4c0a-96f7-72cab7b800d8","type":"YahooComment","name":"\u70d8\u4e7e\u8001\u6a58\u76ae","userkey":"2FXNEWT7YB6Q4SU7QBZFHR5BHM","content":"\u5ee2\u8a71\u4e0d\u7528\u591a\u8aaa\uff0c\u6eff\u53e3\u8b0a\u8a00\u7684\u653f\u5ba2\uff0c\u4e0b\u53f0\u5427........\n\u5ee2\u8a71\u4e0d\u7528\u591a\u8aaa\uff0c\u6eff\u53e3\u8b0a\u8a00\u7684\u653f\u5ba2\uff0c\u4e0b\u53f0\u5427........\n\u5ee2\u8a71\u4e0d\u7528\u591a\u8aaa\uff0c\u6eff\u53e3\u8b0a\u8a00\u7684\u653f\u5ba2\uff0c\u4e0b\u53f0\u5427........","time":1400062658251,"key":"ugccmt-comment_1400062658251-39c8c233-1fcb-4c0a-96f7-72cab7b800d8","url":"https:\/\/tw.news.yahoo.com\/%e5%ba%9c-%e8%8b%a5%e9%81%ad%e7%be%8e%e8%bf%bd%e7%a8%85-%e9%a6%ac%e7%b8%bd%e7%b5%b1%e9%a1%98%e8%be%ad%e8%81%b7-095226801.html","url_title":"\u5e9c\uff1a\u82e5\u906d\u7f8e\u8ffd\u7a05 \u99ac\u7e3d\u7d71\u9858\u8fad\u8077 - Yahoo\u5947\u6469\u65b0\u805e","ueid":"1399626731827","createDate":1400063057000,"creator":"1399626731827","status":0,"reporters":["1399355576669"],"count":0},{"_id":"ugccmt-comment_1400062997454-c959266f-1fc2-4166-84ab-2c03d3c1457a","type":"YahooComment","name":"Regina","userkey":"3XFIUPMUX4VDQ4W63TTN5M3QZA","content":"\u99ac\u7687\u6703\u62dc\u8a17\u7f8e\u570b\u4eba \u6211\u5b89\u5168\u4e0b\u5e84\u5f8c\u4e00\u5b9a\u88dc\u7e73","time":1400062997454,"key":"ugccmt-comment_1400062997454-c959266f-1fc2-4166-84ab-2c03d3c1457a","url":"https:\/\/tw.news.yahoo.com\/\u5e9c-\u82e5\u906d\u7f8e\u8ffd\u7a05-\u99ac\u7e3d\u7d71\u9858\u8fad\u8077-095226801.html","url_title":"\u5e9c\uff1a\u82e5\u906d\u7f8e\u8ffd\u7a05 \u99ac\u7e3d\u7d71\u9858\u8fad\u8077 - Yahoo\u5947\u6469\u65b0\u805e","ueid":"1399355576669","createDate":1400063175000,"creator":"1399355576669","status":0,"reporters":["1399355576669","1399031732963"],"count":0},{"_id":"ugccmt-comment_1400057996165-fefe1dd5-cc10-42c6-9cda-2cc312c9b0de","type":"YahooComment","name":"CHU","userkey":"5EA4ZSHFZNOA5YYDY4RUDXOZZU","content":"\u8aaa\u5230\u66b4\u6c11\u554a~\u9019\u9084\u7b97\u5c0f\u5496\u4e86\n\u53f0\u7063\u7684\u66b4\u6c11\u53ef\u4ee5\u4f54\u64da\u7acb\u6cd5\u6a5f\u95dc~\u5305\u570d\u8b66\u5bdf\u5c40\n\u9084\u6709\u5728\u91ce\u9ee8\u652f\u6301!!!\u5b8c\u5168\u51cc\u99d5\u6cd5\u5f8b\u4e4b\u4e0a~\n\u8d8a\u5357\u5f97\u597d\u597d\u50cf\u6211\u5011\u5171\u7522\u66b4\u6c11\u9032\u6b65\u9ee8\u597d\u597d\u5b78\u7fd2","time":1400057996165,"key":"ugccmt-comment_1400057996165-fefe1dd5-cc10-42c6-9cda-2cc312c9b0de","url":"https:\/\/tw.news.yahoo.com\/\u8d8a\u5357\u66b4\u52d5\u7838\u6436\u53f0\u5546-\u76db\u50b3\u9ed8\u8a31\u66b4\u6c11\u767c\u6d293\u5929-000700396.html","url_title":"\u8d8a\u5357\u66b4\u52d5\u7838\u6436\u53f0\u5546 \u76db\u50b3\u9ed8\u8a31\u66b4\u6c11\u767c\u6d293\u5929 - Yahoo\u5947\u6469\u65b0\u805e","ueid":"1399434983189","createDate":1400058040000,"creator":"1399434983189","status":0,"reporters":["1399434983189"],"count":0},{"_id":"ugccmt-comment_1399115168140-01b156c3-48e2-40e0-a416-9497e04592b3","type":"YahooComment","name":"\u963f\u798f","userkey":"72ZRTNTZ6ONE7HGWLKMKOQOU4Q","content":"\u7da0\u71df\u7684\u8521\u540c\u69ae\u7acb\u59d4\u4e2d\u98a8\u6b7b\u4e86\u5f8c,\u85cd\u71df\u7db2\u53cb\u90fd\u795d\u4ed6\u4e00\u8def\u597d\u8d70,\u6c92\u6709\u8b1b\u72e0\u6bd2\u7684\u8a71!\n\u73fe\u5728\u770b\u770b\u7da0\u71df\u7db2\u53cb\u7684\u767c\u8a00,\u96e3\u602a\u5927\u5bb6\u6703\u7f75\u4ed6\u5011\u662f\u7da0\u755c.\u7272!\n\u653f\u6cbb\u7acb\u5834\u53ef\u4ee5\u4e0d\u540c,\u4f46\u5225\u6c92\u4e86\u4eba\u6027!","time":1399115168140,"key":"ugccmt-comment_1399115168140-01b156c3-48e2-40e0-a416-9497e04592b3","url":"https:\/\/tw.news.yahoo.com\/%e5%85%ac%e4%ba%8b%e8%ab%87-%e5%8d%8a-%e9%a6%ac-%e7%9c%9f%e7%9a%84%e5%be%88%e6%93%94%e5%bf%83%e5%aa%bd-220008716.html","url_title":"\u516c\u4e8b\u8ac7\u4e00\u534a\u2026\u99ac\ufe30\u771f\u7684\u5f88\u64d4\u5fc3\u5abd - Yahoo\u5947\u6469\u65b0\u805e","ueid":"1398770484750","createDate":1400064681000,"creator":"1398770484750","status":0,"reporters":["1398770484750"],"count":0},{"_id":"ugccmt-comment_00002S000000000000000000000000-87a7ef6b-1205-4b87-ab5a-68d4b1afda88","type":"YahooComment","name":"Jimmylee","userkey":"76UP4TVQ5FV4IOBJKIOB5YYHBA","content":"\u4e5f\u662f\u53f0\u7063\u7684\u9152\u9b3c\u5427\uff1f\u4e0d\u7136\u600e\u6703\u88ab\u6253\uff1f","time":1400059250461,"key":"ugccmt-comment_00002S000000000000000000000000-87a7ef6b-1205-4b87-ab5a-68d4b1afda88","url":"https:\/\/tw.news.yahoo.com\/%e5%9c%8b%e5%8f%b0%e8%be%a6-%e9%99%b3%e6%98%87%e8%a8%80%e8%ab%96%e4%b8%8d%e4%bb%a3%e8%a1%a8%e5%8f%b0%e5%a4%9a%e6%95%b8-064316700.html","url_title":"\u570b\u53f0\u8fa6\uff1a\u9673\u6607\u8a00\u8ad6\u4e0d\u4ee3\u8868\u53f0\u591a\u6578 - Yahoo\u5947\u6469\u65b0\u805e","ueid":"1398944167435","createDate":1400059245000,"creator":"1398944167435","status":0,"reporters":["1398944167435"],"count":0},{"_id":"ugccmt-comment_1400043813882-58843f3b-1c5d-4325-be7f-9a5a2ba04721","type":"YahooComment","name":"\u4e0d\u662f\u90f5\u5dee\u99ac\u9f8d\uff0c\u662f\u99ac\u5361\u8338\uff01Macaron\uff01","userkey":"7D4BUCQHDNQQCFAVLWODHNXRMU","content":"\u80e1\u5fd7\u660e\u5e02\u5f88\u6b63\u5e38","time":1400043813882,"key":"ugccmt-comment_1400043813882-58843f3b-1c5d-4325-be7f-9a5a2ba04721","url":"https:\/\/tw.news.yahoo.com\/%e9%97%96%e7%a9%ba%e9%96%80%e6%8b%92%e6%8d%95%e7%88%86%e7%99%bc%e6%a7%8d%e6%88%b0-%e5%a5%b3%e8%b3%8a%e4%b8%ad%e5%bd%88%e8%83%8e%e5%85%92%e4%b8%8d%e4%bf%9d-032500319.html","url_title":"\u95d6\u7a7a\u9580\u62d2\u6355\u7206\u767c\u69cd\u6230\u3000\u5973\u8cca\u4e2d\u5f48\u80ce\u5152\u4e0d\u4fdd - Yahoo\u5947\u6469\u65b0\u805e","ueid":"1399972617799","createDate":1400045510000,"creator":"1399972617799","status":0,"reporters":["1399972617799"],"count":0},{"_id":"ugccmt-comment_1399355854753-772bd7b5-66d0-4910-858d-f1809434c97e","type":"YahooComment","name":"O^O","userkey":"7E2Y2BS73QCBXYU3BD32WOEIXU","content":"\u53c8\u662f\u81ea\u7531\u7684\u7de8\u9020\u5c08\u5bb6\u738b\u5bd3x...\u4e0d\u610f\u5916","time":1399355854753,"key":"ugccmt-comment_1399355854753-772bd7b5-66d0-4910-858d-f1809434c97e","url":"https:\/\/tw.news.yahoo.com\/\u570b\u5b89\u6574\u8ecd\u5c0d\u5167-\u99ac\u91cd\u7528\u8abf\u67e5\u7cfb\u7d71-221022131.html","url_title":"\u570b\u5b89\u6574\u8ecd\u5c0d\u5167\uff1f\u99ac\u91cd\u7528\u8abf\u67e5\u7cfb\u7d71 - Yahoo\u5947\u6469\u65b0\u805e","ueid":"1399108633638","createDate":1399391309000,"creator":"1399108633638","status":0,"reporters":["1399108633638"],"count":0},{"_id":"ugccmt-comment_00002S000000000000000000000000-fbcd2f27-aa1b-49e3-976a-c4e91eca3024","type":"YahooComment","name":"No_nickname","userkey":"7GNLCPYWVXEH4GXLO3H6AHYF2I","content":"\u6f14\u5169\u9846\u5b50\u5f48\u7b2c\u4e8c\u96c6\u4f86\u7d66\u8521\u6b63\u5143\u77a7 ??","time":1399643178965,"key":"ugccmt-comment_00002S000000000000000000000000-fbcd2f27-aa1b-49e3-976a-c4e91eca3024","url":"https:\/\/tw.news.yahoo.com\/\u9ec3\u59d3\u6bcd\u5b50\u64cb\u8521\u6b63\u5143\u8eca-\u5c0d\u5411\u516c\u8eca\u884c\u8eca\u8a18\u9304\u5668\u5f71\u7247\u66dd\u5149-110434654.html","url_title":"\u9ec3\u59d3\u6bcd\u5b50\u64cb\u8521\u6b63\u5143\u8eca\uff01\u3000\u5c0d\u5411\u516c\u8eca\u884c\u8eca\u8a18\u9304\u5668\u5f71\u7247\u66dd\u5149 - Yahoo\u5947\u6469\u65b0\u805e","ueid":"1398355728516","createDate":1399643213000,"creator":"1398355728516","status":0,"reporters":["1398355728516"],"count":0},{"_id":"ugccmt-comment_00003S000000000000000000000000-08c71882-7e9b-4523-8269-b482b05004c5","type":"YahooComment","name":"HEY","userkey":"7QNZ24CX3BOTLTRGBXX2XMSR4I","content":"\u5c0d\u554a \u6216\u662f\u91d1\u5c0f\u5200\u6cbb\u7642\u809b\u9580\u8cbb\u7528","time":1399375713291,"key":"ugccmt-comment_00003S000000000000000000000000-08c71882-7e9b-4523-8269-b482b05004c5","url":"https:\/\/tw.news.yahoo.com\/\u5272\u95cc\u5c3e-\u52df\u6b3e\u6050\u6d89\u9055\u6cd5-\u76e3\u5bdf\u9662-\u737b\u91d1\u53ef\u80fd\u6c92\u6536-072721186.html","url_title":"\u300c\u5272\u95cc\u5c3e\u300d\u52df\u6b3e\u6050\u6d89\u9055\u6cd5 \u76e3\u5bdf\u9662\uff1a\u737b\u91d1\u53ef\u80fd\u6c92\u6536 - Yahoo\u5947\u6469\u65b0\u805e","ueid":"1399355576669","createDate":1399375704000,"creator":"1399355576669","status":0,"reporters":["1399355576669"],"count":0},{"_id":"ugccmt-comment_1399537808874-a4481234-38d4-45c7-a20d-5cde31f30443","type":"YahooComment","name":"\u5537...","userkey":"7RBCHOIBFKSYL22TKVNF3EJ34M","content":"\u600e\u9ebc\u6703 \u99ac\u5361\u8338\u4e00\u5750\u5c31\u9738\u5360\u8457\u4e0d\u60f3\u4e0b\u4f86\u4e86 \u9023\u4e0b\u53f0\u8072\u56db\u8d77\u9084\u662f\u7e7c\u7e8c\u81c9\u76ae\u88dd\u539a\u539a\u4e0d\u9858\u4e0b\u53f0","time":1399537808874,"key":"ugccmt-comment_1399537808874-a4481234-38d4-45c7-a20d-5cde31f30443","url":"https:\/\/tw.news.yahoo.com\/\u9ede\u540d\u9078\u7e3d\u7d71-\u90ed\u53f0\u9298-\u4e0d\u8981\u5bb3\u6211-055047334.html","url_title":"\u9ede\u540d\u9078\u7e3d\u7d71 \u90ed\u53f0\u9298\uff1a\u4e0d\u8981\u5bb3\u6211 - Yahoo\u5947\u6469\u65b0\u805e","ueid":"1398750038691","createDate":1399542890000,"creator":"1398750038691","status":0,"reporters":["1399355576669"],"count":0}]};
  },
  componentWillMount: function() {
    this.loadCommentsFromServer();
    // setInterval(this.loadCommentsFromServer, this.props.pollInterval);
  },
  render: function() {
    return (
      <div className="commentBox">
        <h1> 測試用2 </h1>
        <CommentList data={this.state.data} />
      </div>
    );
  }
});

var CommentList = React.createClass({
  render: function() {
    var commentNodes = this.props.data.map(function (comment, index) {
      return <Comment key={index} data={comment}></Comment>;
    });
    return <div className="commentList">{commentNodes}</div>;
  }
});

React.renderComponent(
  <CommentBox  />,
  document.getElementById('confirms')
);
