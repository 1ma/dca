using System;
using System.CommandLine;
using System.CommandLine.Invocation;
using System.IO;
using System.Threading.Tasks;


var intOption = new Option<int>(
    "--int-option",
    getDefaultValue: () => 42,
    description: "An option whose argument is parsed as an int"
);

var boolOption = new Option<bool>(
    "--bool-option",
    "An option whose argument is parsed as a boolean"
);

var fileOption = new Option<FileInfo>(
    "--file-option",
    "An option whose argument is parsed as a FileInfo"
);

var cmd = new RootCommand
{
    intOption,
    boolOption,
    fileOption
};

cmd.Description = "Sample app";
cmd.SetHandler((int i, bool b, FileInfo? f) =>
{
    Console.WriteLine($"The value for --int-option is: {i}");
    Console.WriteLine($"The value for --bool-option is: {b}");
    Console.WriteLine($"The value for --file-option is: {f?.FullName ?? "null"}");
}, intOption, boolOption, fileOption);

var cmd2 = new Command("what");
cmd2.Description = "subcommand description";

cmd.Add(cmd2);

// Parse the incoming args and invoke the handler
return cmd.Invoke(args);
