@extends('layouts.header')
@section('content')

<h3 class="text-danger">Employee Hierarchy</h3>
@include('layouts.breadcrumb')

<script src="{{ asset('js/go.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    init();
});

var nodeIdCounter = -1;
var myDiagram;

function init() {
    var $ = go.GraphObject.make;

    myDiagram = $(go.Diagram, "myDiagramDiv", {
        initialContentAlignment: go.Spot.Center,
        maxSelectionCount: 1,
        "undoManager.isEnabled": true,
        layout: $(go.TreeLayout, {
            angle: 90,
            layerSpacing: 40
        })
    });

    // ---- FIXED LEVEL COLORS ----
    var levelColors = [
        ["#0d6efd", "#66b0ff"],
        ["#198754", "#50d1a0"],
        ["#dc3545", "#ff758f"],
        ["#fd7e14", "#ffc078"],
        ["#6f42c1", "#c8a5ff"]
    ];

    myDiagram.layout.commitNodes = function () {
        go.TreeLayout.prototype.commitNodes.call(myDiagram.layout);
        myDiagram.layout.network.vertexes.each(function (v) {
            if (v.node) {
                var level = v.level % levelColors.length;
                var colors = levelColors[level];
                var shape = v.node.findObject("SHAPE");
                if (shape) {
                    shape.fill = $(go.Brush, "Linear", {
                        0: colors[0], 1: colors[1],
                        start: go.Spot.Top, end: go.Spot.Bottom
                    });
                }
            }
        });
    };

    function textStyle() {
        return { font: "10pt Segoe UI", stroke: "#212529" };
    }

    function findHeadShot(photo) {
        if (!photo || photo === "") return "{{ url('images/profile_images/default.png') }}";
        return "{{ url('images/profile_images') }}/" + photo;
    }

    myDiagram.nodeTemplate =
        $(go.Node, "Auto",
            $(go.Shape, "RoundedRectangle",
                {
                    name: "SHAPE",
                    fill: "white",
                    stroke: "#ccc",
                    strokeWidth: 1,
                    portId: "",
                    fromLinkable: true,
                    toLinkable: true,
                    cursor: "pointer"
                }),
            $(go.Panel, "Vertical",
                { padding: 10 },
                $(go.Picture,
                    {
                        desiredSize: new go.Size(60, 70),
                        margin: 5
                    },
                    new go.Binding("source", "photo", findHeadShot)
                ),
                $(go.TextBlock, textStyle(),
                    { font: "bold 13px Segoe UI" },
                    new go.Binding("text", "name")
                ),
                $(go.TextBlock, textStyle(),
                    { font: "11px Segoe UI", stroke: "#6c757d" },
                    new go.Binding("text", "title")
                ),
                $(go.TextBlock, textStyle(),
                    { font: "10px Segoe UI", stroke: "#999" },
                    new go.Binding("text", "loc")
                )
            )
        );

    myDiagram.linkTemplate =
        $(go.Link,
            { routing: go.Link.Orthogonal, corner: 5 },
            $(go.Shape, { strokeWidth: 2, stroke: "#0d6efd" })
        );

    load();
}

function load() {
    var json = document.getElementById("mySavedModel").value;

    try {
        myDiagram.model = go.Model.fromJson(json);
    } catch (e) {
        console.error("Invalid JSON in model:", e);
    }
}
</script>

<div class="container-fluid mt-3">
<div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <div id="myDiagramDiv" style="width: 100%; height: 500px; border: 1px solid #ccc"></div>
        </div>
    </div>
</div>

<textarea id="mySavedModel" style="display:none;">
{
    "class": "go.TreeModel",
    "nodeDataArray": [
        {!! $fchart !!}
    ]
}
</textarea>

@endsection
