 <ol class="breadcrumb-custom breadcrumb breadcrumb-arrow">
</ol>
<style>
.breadcrumb-custom {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    font-weight: 500;
    font-size: 14px;
    gap: 6px;
    flex-wrap: wrap;
}

.breadcrumb-custom li {
    display: flex;
    align-items: center;
    text-transform: capitalize;
    color: #656871;
}

.breadcrumb-custom li:not(:last-child)::after {
	content: "›";
    margin: 0 6px;
    color: #000;
    font-size: 30px;
    margin-top: -5px;
}

.breadcrumb-custom li a {
    color: #d82323; /* Home in red */
    text-decoration: none;
    font-weight: bold;
}

.breadcrumb-custom li span {
    color: #182a74;
}
</style>