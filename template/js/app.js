function empty (mixedValue){
     return (mixedValue === undefined || 
          mixedValue === null || mixedValue === "" || mixedValue === "0" || mixedValue === 0 ||
		  mixedValue === false || (Array.isArray(mixedValue) && mixedValue.length == 0));
}